<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpellCheckController extends Controller
{
    public function spellCheck(Request $request)
    {
        /**
         * Read the xml and extract original message and language
         */
        $xml = simplexml_load_string($request->get('xml'));
        $originalMessage = (string) $xml->error_message->message[0];
        $language = (string) $xml->error_message->message[0]['language'];

        /**
         * change xml to array
         */
        $initialXml = json_decode(json_encode($xml), true);

        /**
         * Send request to languagetool spell check form
         */
        $response = Http::asForm()->post('https://languagetool.org/api/v2/check', [
            'text' => $originalMessage,
            'language' => $language,
        ])->json();

        // replace the original message issues
        $matches = $response['matches'];
        $correctedMessage = $originalMessage;
        foreach ($matches as $match) {
            // find the word to search for
            $search = substr($originalMessage, $match['offset'], $match['length']);
            $correctedMessage = str_replace($search, $match['replacements'][0]['value'], $correctedMessage);
        }

        // generate the final response
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
