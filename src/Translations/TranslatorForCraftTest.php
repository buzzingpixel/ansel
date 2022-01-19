<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

use PHPUnit\Framework\TestCase;

class TranslatorForCraftTest extends TestCase
{
    private TranslatorForCraft $translator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->translator = new TranslatorForCraft(
            $this->mockCraftTranslator(),
        );
    }

    private function mockCraftTranslator(): CraftTranslatorFacade
    {
        $mock = $this->createMock(
            CraftTranslatorFacade::class,
        );

        $mock->method('translate')->willReturnCallback(
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
