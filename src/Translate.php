<?php

namespace Translator;

use InvalidArgumentException;

final class Translate
{

    private $text;
    private $source;
    private $target;
    private $content;
    private $ttsServiceURL = 'https://translate.google.com.vn/translate_tts?ie=UTF-8&q=%s&tl=%s&client=tw-ob';
    private $serviceURL = 'https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=%s&tl=%s&hl=hl&q=%s';

    /**
     * Translate constructor.
     * @param string $text
     * @param string $source
     * @param string $target
     */
    public function __construct(string $text, string $source, string $target = 'en')
    {
        $this->text = $text;
        $this->source = $source;
        $this->target = $target;

        if (empty($text)) {
            throw new InvalidArgumentException(
                'The text parameter is required.'
            );
        }

        if ($source === $target) {
            throw new InvalidArgumentException(
                'The languages to be translated should be different.'
            );
        }

        $this->content = file_get_contents(
            sprintf(
                $this->serviceURL,
                $target, $source, urlencode($text)
            ),
            $_SERVER['DOCUMENT_ROOT'] . '/transes.html'
        );
        $this->content = json_decode($this->content);
    }

    /**
     * @param string $source
     * @return Translate
     */
    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @param string $target
     * @return Translate
     */
    public function setTarget(string $target): self
    {
        $this->target = $target;

        return  $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return Translate
     */
    public function reverse(): self
    {
        return new self(
            $this->toText(),
            $this->target,
            $this->source
        );
    }

    /**
     * @return array
     */
    public function values(): array
    {
        $object = [
            'source' => null,
            'target' => null
        ];

        if (isset($this->content[0][0])) {
            [$source, $target] = $this->content[0][0];

            return array_merge(
                $object,
                compact('source', 'target')
            );
        }

        return $object;
    }

    /**
     * @return array
     */
    public function tts(): array
    {
        $values = $this->values();

        return [
            'source' => sprintf($this->ttsServiceURL, urlencode($values['source']), $this->source),
            'target' => sprintf($this->ttsServiceURL, urlencode($values['target']), $this->target)
        ];
    }

    /**
     * @return array
     */
    public function words(): array
    {
        if (isset($this->content[1][0][1])) {
            return $this->content[1][0][1];
        }

        return [];
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->content;
    }

    /**
     * @return string | null
     */
    public function toText(): ?string
    {
        if (isset($this->content[0][0][0])) {
            return (string) $this->content[0][0][0];
        }

        return null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'text' => $this->toText(),
            'tts' => $this->tts(),
            'keywords' => $this->keywords(),
            'values' => $this->values()
        ];
    }

    /**
     * @return string | null
     */
    public function __toString(): ?string
    {
        return $this->toText();
    }

}
