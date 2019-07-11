<?php declare(strict_types=1);

namespace Tests\Integration\Model\Webhook;

use GuzzleHttp\Psr7\Uri;
use Hanaboso\CommonsBundle\Enum\ApplicationTypeEnum;
use Hanaboso\CommonsBundle\Enum\AuthorizationTypeEnum;
use Hanaboso\CommonsBundle\Transport\Curl\CurlException;
use Hanaboso\CommonsBundle\Transport\Curl\CurlManager;
use Hanaboso\CommonsBundle\Transport\Curl\Dto\RequestDto;
use Hanaboso\CommonsBundle\Transport\Curl\Dto\ResponseDto;
use Hanaboso\HbPFApplication\Model\Webhook\WebhookApplicationInterface;
use Hanaboso\HbPFApplication\Model\Webhook\WebhookSubscription;
use Hanaboso\PipesPhpSdk\Authorization\Base\ApplicationAbstract;
use Hanaboso\PipesPhpSdk\Authorization\Document\ApplicationInstall;
use Hanaboso\PipesPhpSdk\Authorization\Model\Form\Form;

/**
 * Class WebhookApplication
 *
 * @package Tests\Integration\Model\Webhook
 */
final class WebhookApplication extends ApplicationAbstract implements WebhookApplicationInterface
{

    private const SUBSCRIBE   = 'https://example.com/webhook/subscribe';
    private const UNSUBSCRIBE = 'https://example.com/webhook/unsubscribe';

    /**
     * @var WebhookSubscription[]
     */
    private $subscriptions = [];

    /**
     * WebhookApplication constructor.
     */
    public function __construct()
    {
        $this->subscriptions[] = new WebhookSubscription('node', 'topology');
    }

    /**
     * @return string
     */
    public function getAuthorizationType(): string
    {
        return AuthorizationTypeEnum::BASIC;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return 'webhook';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Webhook';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getName();
    }

    /**
     * @param ApplicationInstall $applicationInstall
     * @param string             $method
     * @param string|null        $url
     * @param string|null        $data
     *
     * @return RequestDto
     * @throws CurlException
     */
    public function getRequestDto(
        ApplicationInstall $applicationInstall,
        string $method,
        ?string $url,
        ?string $data): RequestDto
    {
        $applicationInstall;
        $method;
        $url;
        $data;

        return new RequestDto(CurlManager::METHOD_POST, new Uri('https://example.com'));
    }

    /**
     * @param ApplicationInstall $applicationInstall
     *
     * @return Form
     */
    public function getForm(ApplicationInstall $applicationInstall): Form
    {
        $applicationInstall;

        return new Form();
    }

    /**
     * @param ApplicationInstall $applicationInstall
     * @param array              $settings
     *
     * @return ApplicationInstall
     */
    public function setApplicationSettings(ApplicationInstall $applicationInstall, array $settings): ApplicationInstall
    {
        $settings;

        return $applicationInstall;
    }

    /**
     * @return WebhookSubscription[]
     */
    public function getWebhookSubscriptions(): array
    {
        return $this->subscriptions;
    }

    /**
     * @param WebhookSubscription $subscription
     * @param string              $url
     *
     * @return RequestDto
     * @throws CurlException
     */
    public function getWebhookSubscribeRequestDto(WebhookSubscription $subscription, string $url): RequestDto
    {
        $subscription;

        return (new RequestDto(CurlManager::METHOD_POST, new Uri(self::SUBSCRIBE)))->setBody(json_encode([
            'url' => $url,
        ], JSON_THROW_ON_ERROR));
    }

    /**
     * @param string $id
     *
     * @return RequestDto
     * @throws CurlException
     */
    public function getWebhookUnsubscribeRequestDto(string $id): RequestDto
    {
        return (new RequestDto(CurlManager::METHOD_POST, new Uri(self::UNSUBSCRIBE)))->setBody(json_encode([
            'id' => $id,
        ], JSON_THROW_ON_ERROR));
    }

    /**
     * @param ResponseDto        $dto
     * @param ApplicationInstall $install
     *
     * @return string
     */
    public function processWebhookSubscribeResponse(ResponseDto $dto, ApplicationInstall $install): string
    {
        $install;

        return json_decode($dto->getBody(), TRUE, 512, JSON_THROW_ON_ERROR)['id'];
    }

    /**
     * @param ResponseDto $dto
     *
     * @return bool
     */
    public function processWebhookUnsubscribeResponse(ResponseDto $dto): bool
    {
        return json_decode($dto->getBody(), TRUE, 512, JSON_THROW_ON_ERROR)['success'] ?? FALSE;
    }

    /**
     * @param ApplicationInstall $applicationInstall
     *
     * @return bool
     */
    public function isAuthorized(ApplicationInstall $applicationInstall): bool
    {
        $applicationInstall;

        return TRUE;
    }

    /**
     * @return string
     */
    public function getApplicationType(): string
    {
        return ApplicationTypeEnum::WEBHOOK;
    }

    /**
     * @return Form
     */
    public function getSettingsForm(): Form
    {
        return new Form();
    }

}
