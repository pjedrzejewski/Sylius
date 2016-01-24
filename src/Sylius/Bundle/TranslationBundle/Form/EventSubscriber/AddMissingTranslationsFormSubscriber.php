<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\TranslationBundle\Form\EventSubscriber;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Translation\Model\TranslatableInterface;
use Sylius\Component\Translation\Provider\LocaleProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Intl\Exception\UnexpectedTypeException;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class AddMissingTranslationsFormSubscriber implements EventSubscriberInterface
{
    /**
     * @var FactoryInterface
     */
    private $translationFactory;

    /**
     * @var LocaleProviderInterface
     */
    private $localesProvider;

    /**
     * @param FactoryInterface $translationFactory
     * @param LocaleProviderInterface $localesProvider
     */
    public function __construct(FactoryInterface $translationFactory, LocaleProviderInterface $localesProvider)
    {
        $this->translationFactory = $translationFactory;
        $this->localesProvider = $localesProvider;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $resource = $event->getData();

        if (!$resource instanceof TranslatableInterface) {
            throw new UnexpectedTypeException($resource, TranslatableInterface::class);
        }

        $translations = $resource->getTranslations();

        foreach ($this->localesProvider->getAvailableLocales() as $locale) {
            if (!isset($translations[$locale])) {
                $translation = $this->translationFactory->createNew();
                $translation->setLocale($locale);

                $resource->addTranslation($translation);
            }
        }
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        foreach ($data['translations'] as $locale => $translationData) {
            if (empty($translationData)) {
                $form->remove(sprintf('translations[%s]', $locale));
            }
        }
    }
}
