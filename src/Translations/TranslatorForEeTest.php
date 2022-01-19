<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

use EE_Lang;
use PHPUnit\Framework\TestCase;

class TranslatorForEeTest extends TestCase
{
    private TranslatorForEe $translator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->translator = new TranslatorForEe(
            $this->mockLang(),
        );
    }

    private function mockLang(): EE_Lang
    {
        $mock = $this->createMock(EE_Lang::class);

        $mock->method('line')->willReturnCallback(
            static function (string $which): string {
                return $which . '-lang {{test_replace1}} {{test_replace1}}' .
                    ' {{test_replace2}}';
            }
        );

        return $mock;
    }

    public function test(): void
    {
        self::assertSame(
            'foo-line-lang foo-replace1 foo-replace1 foo-replace2',
            $this->translator->getLineWithReplacements(
                'foo-line',
                [
                    '{{test_replace1}}' => 'foo-replace1',
                    '{{test_replace2}}' => 'foo-replace2',
                    '{{test_replace3}}' => 'foo-replace3',
                ],
            ),
        );
    }
}
