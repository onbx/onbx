<?php
/*
  Copyright (C) 2010 by Ivan Y. Khvostishkov 
 
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
 
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 
  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @since 2010-03-12
 */
onbxImport('OnbxHttpRequest');
onbxImport('OnbxHttpResponse');
onbxImport('OnbxMap');
onbxImport('OnbxHttpUrl');

class OnbxHttpClient {
    public function sendRequest(OnbxHttpRequest $request) {
        // TODO: HttpMethod
        if ($request->getMethod() != 'GET') {
            // TODO: implement POST
            throw new OnbxUnsupportedOperationException('only GET supported now');
        }
        $url = OnbxHttpUrl::parse($request->getUrl());

        $host = ($url->getScheme() == 'https' ? 'ssl://' : null)
            .$url->getHost();

        $port = $url->getPort() != null 
            ? $url->getPort() 
            : ($url->getScheme() == 'https' ? 443 : 80);

        $socket = fsockopen($host, $port, $errno, $errstr);
        if (!$socket) {
            throw new OnbxIOException(
                sprintf('connect to %s:%s failed: %s (%d)',
                    $host, $port, $errstr, $errno));
        }

        $out = sprintf("%s %s HTTP/1.1\r\n",
            $request->getMethod(),
            $url->getPath().($url->getQuery() != null ? "?{$url->getQuery()}" : null));

        $out .= sprintf("Host: %s\r\n",
            $host.($url->getPort() ? ":{$url->getPort()}" : null));
        $out .= "Connection: Close\r\n\r\n";

        for ($written = 0; $written < strlen($out); $written += $fwrite) {
            $fwrite = fwrite($socket, substr($out, $written));
            if ($fwrite === false) {
                break;
            }
        }

        if ($written != strlen($out)) {
            throw new OnbxIOException(
                sprintf('cannot write to socket, %d bytes written of %s expected',
                    $written, strlen($out)));
        }

        $response = null;
        while (!feof($socket)) {
            $response .= fgets($socket, 4096);
        }
        fclose($socket);

        $responseParts = explode("\r\n\r\n", $response);

        $headers = $responseParts[0];
        $headerLines = explode("\r\n", $headers);
        $statusLine = array_shift($headerLines);
        list($responseProto, $status) = explode(' ', $statusLine, 3);

        $result = new OnbxHttpResponse();
        $result->setStatus($status);

        $headersMap = new OnbxMap();
        foreach ($headerLines as $headerLine) {
            list($key, $value) = preg_split('/:\s*/', $headerLine, 2);
            $headersMap->put($key, $value);
        }
        $result->setHeaders($headersMap);

        $body = isset($responseParts[1]) ? $responseParts[1] : null;
        $result->setBody($body);

        return $result;
    }
}
?>
