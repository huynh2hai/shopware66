<?php declare(strict_types=1);

namespace SwagGoogleTagManager\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class HeaderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SystemConfigService $systemConfigService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HeaderPageletLoadedEvent::class => 'onHeaderPageletLoaded'
        ];
    }

    public function onHeaderPageletLoaded(HeaderPageletLoadedEvent $event): void
    {
        $googleTagId = $this->systemConfigService->get('SwagGoogleTagManager.config.googleTagId');

        if (!$googleTagId) {
            return;
        }

        $event->getPagelet()->assign(['google_tag_id' => $googleTagId]);
    }
}
