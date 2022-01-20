<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee;

use BuzzingPixel\Ansel\Translations\TranslatorContract;
use ExpressionEngine\Service\URL\URLFactory;

class Sidebar
{
    private URLFactory $urlFactory;

    private TranslatorContract $translator;

    public function __construct(
        URLFactory $urlFactory,
        TranslatorContract $translator
    ) {
        $this->urlFactory = $urlFactory;
        $this->translator = $translator;
    }

    /**
     * @return array<string, array<string, bool|string>>
     */
    public function get(string $active = ''): array
    {
        $items = [
            'settings' => [
                'content' => $this->translator->getLine('settings'),
                'href' => $this->urlFactory
                    ->make('addons/settings/ansel')
                    ->compile(),
                'isActive' => false,
            ],
            'updates' => [
                'content' => $this->translator->getLine('updates'),
                'href' => $this->urlFactory
                    ->make('addons/settings/ansel/updates')
                    ->compile(),
                'isActive' => false,
            ],
            'license' => [
                'content' => $this->translator->getLine('license'),
                'href' => $this->urlFactory
                    ->make('addons/settings/ansel/license')
                    ->compile(),
                'isActive' => false,
            ],
        ];

        if (isset($items[$active])) {
            $items[$active]['isActive'] = true;
        }

        return $items;
    }
}
