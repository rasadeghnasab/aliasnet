<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SpellCheckTest extends TestCase
{
    public function test_spellCheck_route_exists()
    {
        $response = $this->json('post', 'api/v1/spellCheck');

        $this->assertNotEquals(404, $response->status());
    }

    public function test_spellCheck_and_shows_correct_message()
    {
        Http::fake([
            'languagetool.org/api/v2/check' => Http::response($this->languageToolSampleOutput(), 200, ['Headers']),
        ]);

        $xmlString = <<< EOD
<?xml version="1.0" encoding="UTF-8"?>
<error_messages>
    <error_message>
        <title>This is an Error Message</title>
        <module>book_repository</module>
        <message language="en-gb">I'm writing an error messsage sentense with some speling miscakes in it and Im expecting it to be returned fixed.</message>
    </error_message>
</error_messages>
EOD;
        $data = [
            'xml' => $xmlString,
        ];

        $response = $this->json('post', 'api/v1/spellCheck', $data);
        $response->assertExactJson([
            "error_messages" => [
                "title" => "This is an Error Message",
                "module" => "book_repository",
                "language" => [
                    "code" => "en-gb",
                ],
                "message" => "I'm writing an error message sentence with some spelling mistakes in it and I'm expecting it to be returned fixed.",
                "original_message" => "I'm writing an error messsage sentense with some speling miscakes in it and Im expecting it to be returned fixed.",
            ],
        ]);
    }

    /**
     * a copy of the languageTool spellCheck output for the given message
     * @return array
     */
    private function languageToolSampleOutput(): array
    {
        return [
            "software" => [
                "name" => "LanguageTool",
                "version" => "5.8-SNAPSHOT",
                "buildDate" => "2022-05-17 13:28:35 +0000",
                "apiVersion" => 1,
                "premium" => true,
                "premiumHint" => "You might be missing errors only the Premium version can find. Contact us at support<at>languagetoolplus.com.",
                "status" => "",
            ],
            "warnings" => [
                "incompleteResults" => false,
            ],
            "language" => [
                "name" => "English (GB)",
                "code" => "en-GB",
                "detectedLanguage" => [
                    "name" => "English (US)",
                    "code" => "en-US",
                    "confidence" => 0.99,
                    "source" => "fasttext+commonwords",
                ],
            ],
            "matches" => [
                [
                    "message" => "Possible spelling mistake found.",
                    "shortMessage" => "Spelling mistake",
                    "replacements" => [
                        [
                            "value" => "message",
                        ],
                        [
                            "value" => "messuage",
                        ],
                        [
                            "value" => "mess sage",
                        ],
                    ],
                    "offset" => 21,
                    "length" => 8,
                    "context" => [
                        "text" => "I'm writing an error messsage sentense with some speling miscakes in ...",
                        "offset" => 21,
                        "length" => 8,
                    ],
                    "sentence" => "I'm writing an error messsage sentense with some speling miscakes in it and Im expecting it to be returned fixed.",
                    "type" => [
                        "typeName" => "Other",
                    ],
                    "rule" => [
                        "id" => "MORFOLOGIK_RULE_EN_GB",
                        "description" => "Possible spelling mistake",
                        "issueType" => "misspelling",
                        "category" => [
                            "id" => "TYPOS",
                            "name" => "Possible Typo",
                        ],
                        "isPremium" => false,
                    ],
                    "ignoreForIncompleteSentence" => false,
                    "contextForSureMatch" => 0,
                ],
                [
                    "message" => "Possible spelling mistake found.",
                    "shortMessage" => "Spelling mistake",
                    "replacements" => [
                        [
                            "value" => "sentence",
                        ],
                    ],
                    "offset" => 30,
                    "length" => 8,
                    "context" => [
                        "text" => "I'm writing an error messsage sentense with some speling miscakes in it and Im...",
                        "offset" => 30,
                        "length" => 8,
                    ],
                    "sentence" => "I'm writing an error messsage sentense with some speling miscakes in it and Im expecting it to be returned fixed.",
                    "type" => [
                        "typeName" => "Other",
                    ],
                    "rule" => [
                        "id" => "MORFOLOGIK_RULE_EN_GB",
                        "description" => "Possible spelling mistake",
                        "issueType" => "misspelling",
                        "category" => [
                            "id" => "TYPOS",
                            "name" => "Possible Typo",
                        ],
                        "isPremium" => false,
                    ],
                    "ignoreForIncompleteSentence" => false,
                    "contextForSureMatch" => 0,
                ],
                [
                    "message" => "Possible spelling mistake found.",
                    "shortMessage" => "Spelling mistake",
                    "replacements" => [
                        [
                            "value" => "spelling",
                        ],
                        [
                            "value" => "spewing",
                        ],
                        [
                            "value" => "spieling",
                        ],
                        [
                            "value" => "spiling",
                        ],
                    ],
                    "offset" => 49,
                    "length" => 7,
                    "context" => [
                        "text" => "...ng an error messsage sentense with some speling miscakes in it and Im expecting it to b...",
                        "offset" => 43,
                        "length" => 7,
                    ],
                    "sentence" => "I'm writing an error messsage sentense with some speling miscakes in it and Im expecting it to be returned fixed.",
                    "type" => [
                        "typeName" => "Other",
                    ],
                    "rule" => [
                        "id" => "MORFOLOGIK_RULE_EN_GB",
                        "description" => "Possible spelling mistake",
                        "issueType" => "misspelling",
                        "category" => [
                            "id" => "TYPOS",
                            "name" => "Possible Typo",
                        ],
                        "isPremium" => false,
                    ],
                    "ignoreForIncompleteSentence" => false,
                    "contextForSureMatch" => 0,
                ],
                [
                    "message" => "Possible spelling mistake found.",
                    "shortMessage" => "Spelling mistake",
                    "replacements" => [
                        [
                            "value" => "mistakes",
                        ],
                        [
                            "value" => "mismakes",
                        ],
                    ],
                    "offset" => 57,
                    "length" => 8,
                    "context" => [
                        "text" => "...ror messsage sentense with some speling miscakes in it and Im expecting it to be returne...",
                        "offset" => 43,
                        "length" => 8,
                    ],
                    "sentence" => "I'm writing an error messsage sentense with some speling miscakes in it and Im expecting it to be returned fixed.",
                    "type" => [
                        "typeName" => "Other",
                    ],
                    "rule" => [
                        "id" => "MORFOLOGIK_RULE_EN_GB",
                        "description" => "Possible spelling mistake",
                        "issueType" => "misspelling",
                        "category" => [
                            "id" => "TYPOS",
                            "name" => "Possible Typo",
                        ],
                        "isPremium" => false,
                    ],
                    "ignoreForIncompleteSentence" => false,
                    "contextForSureMatch" => 0,
                ],
                [
                    "message" => "Possible spelling mistake found.",
                    "shortMessage" => "Spelling mistake",
                    "replacements" => [
                        [
                            "value" => "I'm",
                        ],
                    ],
                    "offset" => 76,
                    "length" => 2,
                    "context" => [
                        "text" => "...se with some speling miscakes in it and Im expecting it to be returned fixed.",
                        "offset" => 43,
                        "length" => 2,
                    ],
                    "sentence" => "I'm writing an error messsage sentense with some speling miscakes in it and Im expecting it to be returned fixed.",
                    "type" => [
                        "typeName" => "Other",
                    ],
                    "rule" => [
                        "id" => "EN_CONTRACTION_SPELLING",
                        "description" => "Spelling of English contractions",
                        "issueType" => "misspelling",
                        "category" => [
                            "id" => "TYPOS",
                            "name" => "Possible Typo",
                        ],
                        "isPremium" => false,
                    ],
                    "ignoreForIncompleteSentence" => false,
                    "contextForSureMatch" => 0,
                ],
            ],
            "sentenceRanges" => [
                [
                    0,
                    113,
                ],
            ],
        ];
    }
}
