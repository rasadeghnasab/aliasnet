<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpellCheckController extends Controller
{
    public function spellCheck(Request $request)
    {
        $xml = simplexml_load_string($request->get('xml'));
        $originalMessage = (string) $xml->error_message->message[0];
        $language = (string) $xml->error_message->message[0]['language'];

        $initialXml = json_decode(json_encode($xml), true);

        $response = Http::asForm()->post('https://languagetool.org/api/v2/check', [
            'text' => $originalMessage,
            'language' => $language,
        ])->json();

        $matches = $response['matches'];
        $correctedMessage = $originalMessage;
        foreach ($matches as $match) {
            $search = substr($originalMessage, $match['offset'], $match['length']);
            $correctedMessage = str_replace($search, $match['replacements'][0]['value'], $correctedMessage);
        }

        $response = [
            'error_messages' => [
                'title' => $initialXml['error_message']['title'],
                'module' => $initialXml['error_message']['module'],
                'language' => [
                    'code' => $language,
                ],
                'message' => $correctedMessage,
                'original_message' => $originalMessage,
            ],
        ];

        return response($response);
    }
}
