<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final
 */
class FrontContainer extends \PrestaShop\PrestaShop\Adapter\Container\LegacyContainer
{
    private $parameters = [];
    private $getService;

    public function __construct()
    {
        $this->getService = \Closure::fromCallable([$this, 'getService']);
        $this->parameters = $this->getDefaultParameters();

        $this->services = $this->privates = [];
        $this->syntheticIds = [
            'employee' => true,
            'shop' => true,
        ];
        $this->methodMap = [
            'Monolog\\Handler\\HandlerInterface' => 'getHandlerInterfaceService',
            'PrestaShopCorp\\Billing\\Presenter\\BillingPresenter' => 'getBillingPresenterService',
            'PrestaShopCorp\\Billing\\Services\\BillingService' => 'getBillingServiceService',
            'PrestaShop\\ModuleLibCacheDirectoryProvider\\Cache\\CacheDirectoryProvider' => 'getCacheDirectoryProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookCategoryClient' => 'getFacebookCategoryClientService',
            'PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookClient' => 'getFacebookClientService',
            'PrestaShop\\Module\\PrestashopFacebook\\API\\EventSubscriber\\AccountSuspendedSubscriber' => 'getAccountSuspendedSubscriberService',
            'PrestaShop\\Module\\PrestashopFacebook\\API\\EventSubscriber\\ApiErrorSubscriber' => 'getApiErrorSubscriberService',
            'PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter' => 'getConfigurationAdapterService',
            'PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ToolsAdapter' => 'getToolsAdapterService',
            'PrestaShop\\Module\\PrestashopFacebook\\Buffer\\TemplateBuffer' => 'getTemplateBufferService',
            'PrestaShop\\Module\\PrestashopFacebook\\Config\\Env' => 'getEnvService',
            'PrestaShop\\Module\\PrestashopFacebook\\Dispatcher\\EventDispatcher' => 'getEventDispatcherService',
            'PrestaShop\\Module\\PrestashopFacebook\\Factory\\FacebookEssentialsApiClientFactory' => 'getFacebookEssentialsApiClientFactoryService',
            'PrestaShop\\Module\\PrestashopFacebook\\Factory\\PsApiClientFactory' => 'getPsApiClientFactoryService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\ApiConversionHandler' => 'getApiConversionHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\CategoryMatchHandler' => 'getCategoryMatchHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\ConfigurationHandler' => 'getConfigurationHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\ErrorHandler\\ErrorHandler' => 'getErrorHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\EventBusProductHandler' => 'getEventBusProductHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\MessengerHandler' => 'getMessengerHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\PixelHandler' => 'getPixelHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Handler\\PrevalidationScanRefreshHandler' => 'getPrevalidationScanRefreshHandlerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Manager\\FbeFeatureManager' => 'getFbeFeatureManagerService',
            'PrestaShop\\Module\\PrestashopFacebook\\Presenter\\ModuleUpgradePresenter' => 'getModuleUpgradePresenterService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\AccessTokenProvider' => 'getAccessTokenProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\EventDataProvider' => 'getEventDataProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\FacebookDataProvider' => 'getFacebookDataProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\FbeDataProvider' => 'getFbeDataProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\FbeFeatureDataProvider' => 'getFbeFeatureDataProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\GoogleCategoryProvider' => 'getGoogleCategoryProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\MultishopDataProvider' => 'getMultishopDataProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\PrevalidationScanCacheProvider' => 'getPrevalidationScanCacheProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\PrevalidationScanDataProvider' => 'getPrevalidationScanDataProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductAvailabilityProvider' => 'getProductAvailabilityProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductSyncReportProvider' => 'getProductSyncReportProviderService',
            'PrestaShop\\Module\\PrestashopFacebook\\Repository\\GoogleCategoryRepository' => 'getGoogleCategoryRepositoryService',
            'PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository' => 'getProductRepositoryService',
            'PrestaShop\\Module\\PrestashopFacebook\\Repository\\ServerInformationRepository' => 'getServerInformationRepositoryService',
            'PrestaShop\\Module\\PrestashopFacebook\\Repository\\ShopRepository' => 'getShopRepositoryService',
            'PrestaShop\\Module\\PrestashopFacebook\\Repository\\TabRepository' => 'getTabRepositoryService',
            'PrestaShop\\Module\\PsAccounts\\Presenter\\PsAccountsPresenter' => 'getPsAccountsPresenterService',
            'PrestaShop\\Module\\PsAccounts\\Repository\\UserTokenRepository' => 'getUserTokenRepositoryService',
            'PrestaShop\\Module\\PsAccounts\\Service\\PsAccountsService' => 'getPsAccountsServiceService',
            'PrestaShop\\Module\\PsAccounts\\Service\\PsBillingService' => 'getPsBillingServiceService',
            'PrestaShop\\Module\\Ps_facebook\\Tracker\\Segment' => 'getSegmentService',
            'PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingCarrierController' => 'getPsshippingCarrierControllerService',
            'PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingConfigurationController' => 'getPsshippingConfigurationControllerService',
            'PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingFaqController' => 'getPsshippingFaqControllerService',
            'PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingHomeController' => 'getPsshippingHomeControllerService',
            'PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingKeycloakAuthController' => 'getPsshippingKeycloakAuthControllerService',
            'PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingOrdersController' => 'getPsshippingOrdersControllerService',
            'PrestaShop\\Module\\Psshipping\\Domain\\Carriers\\CarrierRepository' => 'getCarrierRepositoryService',
            'PrestaShop\\Module\\Psshipping\\Domain\\Carriers\\PickupCarrierConfiguration' => 'getPickupCarrierConfigurationService',
            'PrestaShop\\Module\\Psshipping\\Domain\\Carriers\\StandardCarrierConfiguration' => 'getStandardCarrierConfigurationService',
            'PrestaShop\\Module\\Psshipping\\Handler\\ErrorHandler' => 'getErrorHandler2Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Adapter\\ConfigurationAdapter' => 'getConfigurationAdapter2Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Buffer\\TemplateBuffer' => 'getTemplateBuffer2Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Config\\Env' => 'getEnv2Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Conversion\\EnhancedConversionToggle' => 'getEnhancedConversionToggleService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Conversion\\UserDataProvider' => 'getUserDataProviderService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Handler\\ErrorHandler' => 'getErrorHandler3Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Handler\\RemarketingHookHandler' => 'getRemarketingHookHandlerService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\CartEventDataProvider' => 'getCartEventDataProviderService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\PageViewEventDataProvider' => 'getPageViewEventDataProviderService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\ProductDataProvider' => 'getProductDataProviderService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\PurchaseEventDataProvider' => 'getPurchaseEventDataProviderService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\VerificationTagDataProvider' => 'getVerificationTagDataProviderService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\AttributesRepository' => 'getAttributesRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CarrierRepository' => 'getCarrierRepository2Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CategoryRepository' => 'getCategoryRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CountryRepository' => 'getCountryRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CurrencyRepository' => 'getCurrencyRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\LanguageRepository' => 'getLanguageRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\ManufacturerRepository' => 'getManufacturerRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\ProductRepository' => 'getProductRepository2Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\StateRepository' => 'getStateRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\TabRepository' => 'getTabRepository2Service',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\TaxRepository' => 'getTaxRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\VerificationTagRepository' => 'getVerificationTagRepositoryService',
            'PrestaShop\\Module\\PsxMarketingWithGoogle\\Tracker\\Segment' => 'getSegment2Service',
            'PrestaShop\\PrestaShop\\Adapter\\Configuration' => 'getConfigurationService',
            'PrestaShop\\PrestaShop\\Adapter\\ContextStateManager' => 'getContextStateManagerService',
            'PrestaShop\\PrestaShop\\Adapter\\Currency\\CurrencyDataProvider' => 'getCurrencyDataProviderService',
            'PrestaShop\\PrestaShop\\Adapter\\LegacyContext' => 'getLegacyContextService',
            'PrestaShop\\PrestaShop\\Adapter\\Tools' => 'getToolsService',
            'PrestaShop\\PrestaShop\\Core\\Hook\\HookModuleFilter' => 'getHookModuleFilterService',
            'PrestaShop\\PrestaShop\\Core\\Localization\\Locale\\Repository' => 'getRepositoryService',
            'PrestaShop\\PsAccountsInstaller\\Installer\\Facade\\PsAccounts' => 'getPsAccountsService',
            'PrestaShop\\PsAccountsInstaller\\Installer\\Installer' => 'getInstallerService',
            'PsCheckout\\Api\\Http\\CheckoutHttpClient' => 'getCheckoutHttpClientService',
            'PsCheckout\\Api\\Http\\Configuration\\CheckoutClientConfigurationBuilder' => 'getCheckoutClientConfigurationBuilderService',
            'PsCheckout\\Api\\Http\\Configuration\\OrderHttpClientConfigurationBuilder' => 'getOrderHttpClientConfigurationBuilderService',
            'PsCheckout\\Api\\Http\\Configuration\\OrderShipmentTrackingConfigurationBuilder' => 'getOrderShipmentTrackingConfigurationBuilderService',
            'PsCheckout\\Api\\Http\\OrderHttpClient' => 'getOrderHttpClientService',
            'PsCheckout\\Api\\Http\\OrderShipmentTrackingHttpClient' => 'getOrderShipmentTrackingHttpClientService',
            'PsCheckout\\Cache\\Array\\PayPalOrder' => 'getPayPalOrderService',
            'PsCheckout\\Cache\\Array\\ShippingTracking' => 'getShippingTrackingService',
            'PsCheckout\\Cache\\FileSystem\\PayPalOrder' => 'getPayPalOrder2Service',
            'PsCheckout\\Cache\\FileSystem\\ShippingTracking' => 'getShippingTracking2Service',
            'PsCheckout\\Core\\Customer\\Action\\ExpressCheckoutAction' => 'getExpressCheckoutActionService',
            'PsCheckout\\Core\\FundingSource\\Factory\\FundingSourceTokenFactory' => 'getFundingSourceTokenFactoryService',
            'PsCheckout\\Core\\OrderState\\Action\\ChangeOrderStateAction' => 'getChangeOrderStateActionService',
            'PsCheckout\\Core\\OrderState\\Action\\SetCompletedOrderStateAction' => 'getSetCompletedOrderStateActionService',
            'PsCheckout\\Core\\OrderState\\Action\\SetDeclinedOrderStateAction' => 'getSetDeclinedOrderStateActionService',
            'PsCheckout\\Core\\OrderState\\Action\\SetPendingOrderStateAction' => 'getSetPendingOrderStateActionService',
            'PsCheckout\\Core\\OrderState\\Action\\SetRefundedOrderStateAction' => 'getSetRefundedOrderStateActionService',
            'PsCheckout\\Core\\OrderState\\Action\\SetReversedOrderStateAction' => 'getSetReversedOrderStateActionService',
            'PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper' => 'getOrderStateMapperService',
            'PsCheckout\\Core\\Order\\Action\\CreateOrderAction' => 'getCreateOrderActionService',
            'PsCheckout\\Core\\Order\\Action\\CreateOrderPaymentAction' => 'getCreateOrderPaymentActionService',
            'PsCheckout\\Core\\Order\\Action\\CreateValidateOrderDataAction' => 'getCreateValidateOrderDataActionService',
            'PsCheckout\\Core\\Order\\Action\\ValidateOrderAction' => 'getValidateOrderActionService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\AmountBreakdownNode' => 'getAmountBreakdownNodeService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\ApplicationContextNodeBuilder' => 'getApplicationContextNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\BaseNodeBuilder' => 'getBaseNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\CardPaymentSourceNodeBuilder' => 'getCardPaymentSourceNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\GooglePayPaymentSourceNodeBuilder' => 'getGooglePayPaymentSourceNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\PayPalPaymentSourceNodeBuilder' => 'getPayPalPaymentSourceNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\PayerNodeBuilder' => 'getPayerNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\PaymentSource\\VenmoPaymentSourceNodeBuilder' => 'getVenmoPaymentSourceNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\PuiPaymentSourceNodeBuilder' => 'getPuiPaymentSourceNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\ShippingNodeBuilder' => 'getShippingNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\Node\\SupplementaryDataNodeBuilder' => 'getSupplementaryDataNodeBuilderService',
            'PsCheckout\\Core\\Order\\Builder\\OrderPayloadBuilder' => 'getOrderPayloadBuilderService',
            'PsCheckout\\Core\\Order\\Exception\\Handler\\OrderCreationExceptionHandler' => 'getOrderCreationExceptionHandlerService',
            'PsCheckout\\Core\\Order\\Processor\\CreateOrderProcessor' => 'getCreateOrderProcessorService',
            'PsCheckout\\Core\\Order\\Validator\\CheckoutValidator' => 'getCheckoutValidatorService',
            'PsCheckout\\Core\\Order\\Validator\\OrderAmountValidator' => 'getOrderAmountValidatorService',
            'PsCheckout\\Core\\Order\\Validator\\OrderAuthorizationValidator' => 'getOrderAuthorizationValidatorService',
            'PsCheckout\\Core\\PayPal\\ApplePay\\Builder\\ApplePayPaymentRequestDataBuilder' => 'getApplePayPaymentRequestDataBuilderService',
            'PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator' => 'getCard3DSecureValidatorService',
            'PsCheckout\\Core\\PayPal\\GooglePay\\Builder\\GooglePayPaymentRequestDataBuilder' => 'getGooglePayPaymentRequestDataBuilderService',
            'PsCheckout\\Core\\PayPal\\OAuth\\OAuthService' => 'getOAuthServiceService',
            'PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction' => 'getPayPalCheckOrderStatusActionService',
            'PsCheckout\\Core\\PayPal\\Order\\Action\\CancelPayPalOrderAction' => 'getCancelPayPalOrderActionService',
            'PsCheckout\\Core\\PayPal\\Order\\Action\\CapturePayPalOrderAction' => 'getCapturePayPalOrderActionService',
            'PsCheckout\\Core\\PayPal\\Order\\Action\\CreatePayPalOrderAction' => 'getCreatePayPalOrderActionService',
            'PsCheckout\\Core\\PayPal\\Order\\Action\\RefundPayPalOrderAction' => 'getRefundPayPalOrderActionService',
            'PsCheckout\\Core\\PayPal\\Order\\Action\\UpdatePayPalOrderPurchaseUnitAction' => 'getUpdatePayPalOrderPurchaseUnitActionService',
            'PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache' => 'getPayPalOrderCacheService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderApprovalReversedEventHandler' => 'getOrderApprovalReversedEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderApprovedEventHandler' => 'getOrderApprovedEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderCompletedEventHandler' => 'getOrderCompletedEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\PayPalEventDispatcher' => 'getPayPalEventDispatcherService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentCompletedEventHandler' => 'getPaymentCompletedEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentDeniedEventHandler' => 'getPaymentDeniedEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentPendingEventHandler' => 'getPaymentPendingEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentRefundedEventHandler' => 'getPaymentRefundedEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentReversedEventHandler' => 'getPaymentReversedEventHandlerService',
            'PsCheckout\\Core\\PayPal\\Order\\Processor\\CreatePayPalOrderProcessor' => 'getCreatePayPalOrderProcessorService',
            'PsCheckout\\Core\\PayPal\\Order\\Processor\\UpdateExternalPayPalOrderProcessor' => 'getUpdateExternalPayPalOrderProcessorService',
            'PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider' => 'getPayPalOrderProviderService',
            'PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderTranslationProvider' => 'getPayPalOrderTranslationProviderService',
            'PsCheckout\\Core\\PayPal\\Order\\Validator\\CreatedPayPalOrderValidator' => 'getCreatedPayPalOrderValidatorService',
            'PsCheckout\\Core\\PayPal\\Order\\Validator\\CreatedPayUponInvoiceOrderValidator' => 'getCreatedPayUponInvoiceOrderValidatorService',
            'PsCheckout\\Core\\PayPal\\Refund\\Provider\\PayPalRefundOrderProvider' => 'getPayPalRefundOrderProviderService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Action\\AddTrackingAction' => 'getAddTrackingActionService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Action\\AddTrackingActionInterface' => 'getAddTrackingActionInterfaceService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Action\\ProcessExternalShipmentAction' => 'getProcessExternalShipmentActionService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingBaseNodeBuilder' => 'getTrackingBaseNodeBuilderService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingCarrierModuleNodeBuilder' => 'getTrackingCarrierModuleNodeBuilderService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingItemsNodeBuilder' => 'getTrackingItemsNodeBuilderService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\TrackingPayloadBuilder' => 'getTrackingPayloadBuilderService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Cache\\ShippingTrackingCache' => 'getShippingTrackingCacheService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ExternalShipmentProcessor' => 'getExternalShipmentProcessorService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ShipmentProcessor' => 'getShipmentProcessorService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ShipmentProcessorInterface' => 'getShipmentProcessorInterfaceService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingApiService' => 'getTrackingApiServiceService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingDatabaseHandler' => 'getTrackingDatabaseHandlerService',
            'PsCheckout\\Core\\PayPal\\ShippingTracking\\Validator\\OrderTrackerValidator' => 'getOrderTrackerValidatorService',
            'PsCheckout\\Core\\PaymentToken\\Action\\DeletePaymentTokenAction' => 'getDeletePaymentTokenActionService',
            'PsCheckout\\Core\\PaymentToken\\Action\\SavePaymentTokenAction' => 'getSavePaymentTokenActionService',
            'PsCheckout\\Core\\Settings\\Configuration\\PayPalConfiguration' => 'getPayPalConfigurationService',
            'PsCheckout\\Core\\Settings\\Configuration\\PayPalPayLaterConfiguration' => 'getPayPalPayLaterConfigurationService',
            'PsCheckout\\Core\\Settings\\Configuration\\PayPalSdkConfiguration' => 'getPayPalSdkConfigurationService',
            'PsCheckout\\Core\\WebhookDispatcher\\Action\\CheckPSLSignatureAction' => 'getCheckPSLSignatureActionService',
            'PsCheckout\\Core\\WebhookDispatcher\\Processor\\DispatchWebhookProcessor' => 'getDispatchWebhookProcessorService',
            'PsCheckout\\Core\\WebhookDispatcher\\Provider\\WebhookBodyProvider' => 'getWebhookBodyProviderService',
            'PsCheckout\\Core\\WebhookDispatcher\\Provider\\WebhookHeaderProvider' => 'getWebhookHeaderProviderService',
            'PsCheckout\\Core\\WebhookDispatcher\\Validator\\BodyValuesValidator' => 'getBodyValuesValidatorService',
            'PsCheckout\\Core\\WebhookDispatcher\\Validator\\HeaderValuesValidator' => 'getHeaderValuesValidatorService',
            'PsCheckout\\Core\\WebhookDispatcher\\Validator\\WebhookShopIdValidator' => 'getWebhookShopIdValidatorService',
            'PsCheckout\\Core\\Webhook\\Handler\\WebhookEventConfigurationUpdatedHandler' => 'getWebhookEventConfigurationUpdatedHandlerService',
            'PsCheckout\\Core\\Webhook\\Handler\\WebhookHandler' => 'getWebhookHandlerService',
            'PsCheckout\\Core\\Webhook\\Service\\WebhookSecretToken' => 'getWebhookSecretTokenService',
            'PsCheckout\\Infrastructure\\Action\\AddProductToCartAction' => 'getAddProductToCartActionService',
            'PsCheckout\\Infrastructure\\Action\\CreateOrUpdateAddressAction' => 'getCreateOrUpdateAddressActionService',
            'PsCheckout\\Infrastructure\\Action\\CustomerAuthenticationAction' => 'getCustomerAuthenticationActionService',
            'PsCheckout\\Infrastructure\\Action\\CustomerNotifyAction' => 'getCustomerNotifyActionService',
            'PsCheckout\\Infrastructure\\Adapter\\Address' => 'getAddressService',
            'PsCheckout\\Infrastructure\\Adapter\\Cart' => 'getCartService',
            'PsCheckout\\Infrastructure\\Adapter\\Configuration' => 'getConfiguration2Service',
            'PsCheckout\\Infrastructure\\Adapter\\Context' => 'getContextService',
            'PsCheckout\\Infrastructure\\Adapter\\Country' => 'getCountryService',
            'PsCheckout\\Infrastructure\\Adapter\\Currency' => 'getCurrencyService',
            'PsCheckout\\Infrastructure\\Adapter\\Customer' => 'getCustomerService',
            'PsCheckout\\Infrastructure\\Adapter\\Language' => 'getLanguageService',
            'PsCheckout\\Infrastructure\\Adapter\\Link' => 'getLinkService',
            'PsCheckout\\Infrastructure\\Adapter\\ShopContext' => 'getShopContextService',
            'PsCheckout\\Infrastructure\\Adapter\\SystemConfiguration' => 'getSystemConfigurationService',
            'PsCheckout\\Infrastructure\\Adapter\\Tools' => 'getTools2Service',
            'PsCheckout\\Infrastructure\\Adapter\\Validate' => 'getValidateService',
            'PsCheckout\\Infrastructure\\Environment\\Env' => 'getEnv3Service',
            'PsCheckout\\Infrastructure\\Environment\\EnvLoader' => 'getEnvLoaderService',
            'PsCheckout\\Infrastructure\\Logger\\LoggerFactory' => 'getLoggerFactoryService',
            'PsCheckout\\Infrastructure\\Logger\\LoggerFileFinder' => 'getLoggerFileFinderService',
            'PsCheckout\\Infrastructure\\Logger\\LoggerFileReader' => 'getLoggerFileReaderService',
            'PsCheckout\\Infrastructure\\Logger\\LoggerHandlerFactory' => 'getLoggerHandlerFactoryService',
            'PsCheckout\\Infrastructure\\Repository\\AddressRepository' => 'getAddressRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\CartRepository' => 'getCartRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\ConfigurationRepository' => 'getConfigurationRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\CountryRepository' => 'getCountryRepository2Service',
            'PsCheckout\\Infrastructure\\Repository\\CurrencyRepository' => 'getCurrencyRepository2Service',
            'PsCheckout\\Infrastructure\\Repository\\CustomerRepository' => 'getCustomerRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\FundingSourceRepository' => 'getFundingSourceRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\GenderRepository' => 'getGenderRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\LanguageRepository' => 'getLanguageRepository2Service',
            'PsCheckout\\Infrastructure\\Repository\\OrderHistoryRepository' => 'getOrderHistoryRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\OrderRepository' => 'getOrderRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\OrderStateRepository' => 'getOrderStateRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PayPalCustomerRepository' => 'getPayPalCustomerRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PayPalOrderAuthorizationRepository' => 'getPayPalOrderAuthorizationRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PayPalOrderCaptureRepository' => 'getPayPalOrderCaptureRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PayPalOrderMatrixRepository' => 'getPayPalOrderMatrixRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PayPalOrderPurchaseUnitRepository' => 'getPayPalOrderPurchaseUnitRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PayPalOrderRefundRepository' => 'getPayPalOrderRefundRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository' => 'getPayPalOrderRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PaymentTokenRepository' => 'getPaymentTokenRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\PsAccountRepository' => 'getPsAccountRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\ShippingTrackingRepository' => 'getShippingTrackingRepositoryService',
            'PsCheckout\\Infrastructure\\Repository\\StateRepository' => 'getStateRepository2Service',
            'PsCheckout\\Infrastructure\\Validator\\FrontControllerValidator' => 'getFrontControllerValidatorService',
            'PsCheckout\\Infrastructure\\Validator\\MerchantValidator' => 'getMerchantValidatorService',
            'PsCheckout\\Infrastructure\\Validator\\PayLaterValidator' => 'getPayLaterValidatorService',
            'PsCheckout\\Module\\Presentation\\Translator' => 'getTranslatorService',
            'PsCheckout\\Presentation\\Presenter\\Cart\\CartPresenter' => 'getCartPresenterService',
            'PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourcePresenter' => 'getFundingSourcePresenterService',
            'PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTokenPresenter' => 'getFundingSourceTokenPresenterService',
            'PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider' => 'getFundingSourceTranslationProviderService',
            'PsCheckout\\Presentation\\Presenter\\FundingSource\\LogoPresenter' => 'getLogoPresenterService',
            'PsCheckout\\Presentation\\Presenter\\OrderSummary\\OrderSummaryPresenter' => 'getOrderSummaryPresenterService',
            'PsCheckout\\Presentation\\Presenter\\Settings\\Front\\FrontSettingsPresenter' => 'getFrontSettingsPresenterService',
            'PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\ConfigurationModule' => 'getConfigurationModuleService',
            'PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\LinkModule' => 'getLinkModuleService',
            'PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\MediaModule' => 'getMediaModuleService',
            'PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\PayPalModule' => 'getPayPalModuleService',
            'PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\TranslationModule' => 'getTranslationModuleService',
            'PsCheckout\\Presentation\\Presenter\\Settings\\Front\\SupportedCardBrandsPresenter' => 'getSupportedCardBrandsPresenterService',
            'PsCheckout\\Utility\\Common\\InputStreamUtility' => 'getInputStreamUtilityService',
            'Psr\\Log\\LoggerInterface' => 'getLoggerInterfaceService',
            'annotation_reader' => 'getAnnotationReaderService',
            'array' => 'getArrayService',
            'configuration' => 'getConfiguration3Service',
            'container.env_var_processors_locator' => 'getContainer_EnvVarProcessorsLocatorService',
            'context' => 'getContext2Service',
            'db' => 'getDbService',
            'doctrine' => 'getDoctrineService',
            'doctrine.dbal.default_connection' => 'getDoctrine_Dbal_DefaultConnectionService',
            'doctrine.orm.default_entity_manager' => 'getDoctrine_Orm_DefaultEntityManagerService',
            'hashing' => 'getHashingService',
            'klaviyops.klaviyo_api_wrapper' => 'getKlaviyops_KlaviyoApiWrapperService',
            'klaviyops.klaviyo_service.coupon_generator' => 'getKlaviyops_KlaviyoService_CouponGeneratorService',
            'klaviyops.klaviyo_service.customer_event_service' => 'getKlaviyops_KlaviyoService_CustomerEventServiceService',
            'klaviyops.klaviyo_service.order_event' => 'getKlaviyops_KlaviyoService_OrderEventService',
            'klaviyops.klaviyo_service.profile_event' => 'getKlaviyops_KlaviyoService_ProfileEventService',
            'klaviyops.module' => 'getKlaviyops_ModuleService',
            'klaviyops.prestashop_components.context' => 'getKlaviyops_PrestashopComponents_ContextService',
            'klaviyops.prestashop_services.cart_rule' => 'getKlaviyops_PrestashopServices_CartRuleService',
            'klaviyops.prestashop_services.context' => 'getKlaviyops_PrestashopServices_ContextService',
            'klaviyops.prestashop_services.customer' => 'getKlaviyops_PrestashopServices_CustomerService',
            'klaviyops.prestashop_services.datetime' => 'getKlaviyops_PrestashopServices_DatetimeService',
            'klaviyops.prestashop_services.logger' => 'getKlaviyops_PrestashopServices_LoggerService',
            'klaviyops.prestashop_services.order' => 'getKlaviyops_PrestashopServices_OrderService',
            'klaviyops.prestashop_services.product' => 'getKlaviyops_PrestashopServices_ProductService',
            'klaviyops.prestashop_services.validate' => 'getKlaviyops_PrestashopServices_ValidateService',
            'klaviyops.util_services.csv' => 'getKlaviyops_UtilServices_CsvService',
            'klaviyops.util_services.env' => 'getKlaviyops_UtilServices_EnvService',
            'prestashop.adapter.data_provider.country' => 'getPrestashop_Adapter_DataProvider_CountryService',
            'prestashop.adapter.environment' => 'getPrestashop_Adapter_EnvironmentService',
            'prestashop.adapter.module.repository.module_repository' => 'getPrestashop_Adapter_Module_Repository_ModuleRepositoryService',
            'prestashop.adapter.validate' => 'getPrestashop_Adapter_ValidateService',
            'prestashop.core.circuit_breaker.advanced_factory' => 'getPrestashop_Core_CircuitBreaker_AdvancedFactoryService',
            'prestashop.core.circuit_breaker.cache' => 'getPrestashop_Core_CircuitBreaker_CacheService',
            'prestashop.core.circuit_breaker.doctrine_cache' => 'getPrestashop_Core_CircuitBreaker_DoctrineCacheService',
            'prestashop.core.circuit_breaker.storage' => 'getPrestashop_Core_CircuitBreaker_StorageService',
            'prestashop.core.filter.front_end_object.cart' => 'getPrestashop_Core_Filter_FrontEndObject_CartService',
            'prestashop.core.filter.front_end_object.configuration' => 'getPrestashop_Core_Filter_FrontEndObject_ConfigurationService',
            'prestashop.core.filter.front_end_object.customer' => 'getPrestashop_Core_Filter_FrontEndObject_CustomerService',
            'prestashop.core.filter.front_end_object.main' => 'getPrestashop_Core_Filter_FrontEndObject_MainService',
            'prestashop.core.filter.front_end_object.product' => 'getPrestashop_Core_Filter_FrontEndObject_ProductService',
            'prestashop.core.filter.front_end_object.product_collection' => 'getPrestashop_Core_Filter_FrontEndObject_ProductCollectionService',
            'prestashop.core.filter.front_end_object.search_result_product' => 'getPrestashop_Core_Filter_FrontEndObject_SearchResultProductService',
            'prestashop.core.filter.front_end_object.search_result_product_collection' => 'getPrestashop_Core_Filter_FrontEndObject_SearchResultProductCollectionService',
            'prestashop.core.filter.front_end_object.shop' => 'getPrestashop_Core_Filter_FrontEndObject_ShopService',
            'prestashop.core.localization.cache.adapter' => 'getPrestashop_Core_Localization_Cache_AdapterService',
            'prestashop.core.localization.cldr.cache.adapter' => 'getPrestashop_Core_Localization_Cldr_Cache_AdapterService',
            'prestashop.core.localization.cldr.datalayer.locale_cache' => 'getPrestashop_Core_Localization_Cldr_Datalayer_LocaleCacheService',
            'prestashop.core.localization.cldr.datalayer.locale_reference' => 'getPrestashop_Core_Localization_Cldr_Datalayer_LocaleReferenceService',
            'prestashop.core.localization.cldr.locale_data_source' => 'getPrestashop_Core_Localization_Cldr_LocaleDataSourceService',
            'prestashop.core.localization.cldr.locale_repository' => 'getPrestashop_Core_Localization_Cldr_LocaleRepositoryService',
            'prestashop.core.localization.cldr.reader' => 'getPrestashop_Core_Localization_Cldr_ReaderService',
            'prestashop.core.localization.currency.datasource' => 'getPrestashop_Core_Localization_Currency_DatasourceService',
            'prestashop.core.localization.currency.middleware.cache' => 'getPrestashop_Core_Localization_Currency_Middleware_CacheService',
            'prestashop.core.localization.currency.middleware.database' => 'getPrestashop_Core_Localization_Currency_Middleware_DatabaseService',
            'prestashop.core.localization.currency.middleware.installed' => 'getPrestashop_Core_Localization_Currency_Middleware_InstalledService',
            'prestashop.core.localization.currency.middleware.reference' => 'getPrestashop_Core_Localization_Currency_Middleware_ReferenceService',
            'prestashop.core.localization.currency.repository' => 'getPrestashop_Core_Localization_Currency_RepositoryService',
            'prestashop.core.localization.locale.context_locale' => 'getPrestashop_Core_Localization_Locale_ContextLocaleService',
            'prestashop.core.string.character_cleaner' => 'getPrestashop_Core_String_CharacterCleanerService',
            'prestashop.database.naming_strategy' => 'getPrestashop_Database_NamingStrategyService',
            'prestashop.translation.translator_language_loader' => 'getPrestashop_Translation_TranslatorLanguageLoaderService',
            'product_comment_criterion_repository' => 'getProductCommentCriterionRepositoryService',
            'product_comment_repository' => 'getProductCommentRepositoryService',
            'ps_accounts.facade' => 'getPsAccounts_FacadeService',
            'ps_accounts.installer' => 'getPsAccounts_InstallerService',
            'ps_checkout.db' => 'getPsCheckout_DbService',
            'ps_checkout.module' => 'getPsCheckout_ModuleService',
            'ps_facebook' => 'getPsFacebookService',
            'ps_facebook.billing_env' => 'getPsFacebook_BillingEnvService',
            'ps_facebook.cache' => 'getPsFacebook_CacheService',
            'ps_facebook.context' => 'getPsFacebook_ContextService',
            'ps_facebook.controller' => 'getPsFacebook_ControllerService',
            'ps_facebook.cookie' => 'getPsFacebook_CookieService',
            'ps_facebook.currency' => 'getPsFacebook_CurrencyService',
            'ps_facebook.language' => 'getPsFacebook_LanguageService',
            'ps_facebook.link' => 'getPsFacebook_LinkService',
            'ps_facebook.shop' => 'getPsFacebook_ShopService',
            'ps_facebook.smarty' => 'getPsFacebook_SmartyService',
            'psshipping' => 'getPsshippingService',
            'psshipping.context' => 'getPsshipping_ContextService',
            'psshipping.helper.config' => 'getPsshipping_Helper_ConfigService',
            'psshipping.ps_billings_context_wrapper' => 'getPsshipping_PsBillingsContextWrapperService',
            'psshipping.ps_billings_facade' => 'getPsshipping_PsBillingsFacadeService',
            'psshipping.ps_billings_service' => 'getPsshipping_PsBillingsServiceService',
            'psxmarketingwithgoogle' => 'getPsxmarketingwithgoogleService',
            'psxmarketingwithgoogle.billing_env' => 'getPsxmarketingwithgoogle_BillingEnvService',
            'psxmarketingwithgoogle.cart' => 'getPsxmarketingwithgoogle_CartService',
            'psxmarketingwithgoogle.context' => 'getPsxmarketingwithgoogle_ContextService',
            'psxmarketingwithgoogle.controller' => 'getPsxmarketingwithgoogle_ControllerService',
            'psxmarketingwithgoogle.cookie' => 'getPsxmarketingwithgoogle_CookieService',
            'psxmarketingwithgoogle.country' => 'getPsxmarketingwithgoogle_CountryService',
            'psxmarketingwithgoogle.currency' => 'getPsxmarketingwithgoogle_CurrencyService',
            'psxmarketingwithgoogle.customer' => 'getPsxmarketingwithgoogle_CustomerService',
            'psxmarketingwithgoogle.db' => 'getPsxmarketingwithgoogle_DbService',
            'psxmarketingwithgoogle.language' => 'getPsxmarketingwithgoogle_LanguageService',
            'psxmarketingwithgoogle.link' => 'getPsxmarketingwithgoogle_LinkService',
            'psxmarketingwithgoogle.shop' => 'getPsxmarketingwithgoogle_ShopService',
            'psxmarketingwithgoogle.smarty' => 'getPsxmarketingwithgoogle_SmartyService',
            'prestashop.adapter.tools' => 'getPrestashop_Adapter_ToolsService',
        ];
        $this->aliases = [
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\GoogleCategoryProviderInterface' => 'PrestaShop\\Module\\PrestashopFacebook\\Provider\\GoogleCategoryProvider',
            'PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductAvailabilityProviderInterface' => 'PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductAvailabilityProvider',
            'PrestaShop\\PrestaShop\\Core\\Currency\\CurrencyDataProviderInterface' => 'PrestaShop\\PrestaShop\\Adapter\\Currency\\CurrencyDataProvider',
            'PrestaShop\\PrestaShop\\Core\\Localization\\LocaleInterface' => 'prestashop.core.localization.locale.context_locale',
            'database_connection' => 'doctrine.dbal.default_connection',
            'doctrine.orm.entity_manager' => 'doctrine.orm.default_entity_manager',
            'prestashop.adapter.context_state_manager' => 'PrestaShop\\PrestaShop\\Adapter\\ContextStateManager',
            'prestashop.adapter.data_provider.currency' => 'PrestaShop\\PrestaShop\\Adapter\\Currency\\CurrencyDataProvider',
            'prestashop.adapter.legacy.configuration' => 'PrestaShop\\PrestaShop\\Adapter\\Configuration',
            'prestashop.adapter.legacy.context' => 'PrestaShop\\PrestaShop\\Adapter\\LegacyContext',
            'prestashop.core.localization.cldr.datalayer.top_layer' => 'prestashop.core.localization.cldr.datalayer.locale_cache',
            'prestashop.core.localization.currency.middleware.top_layer' => 'prestashop.core.localization.currency.middleware.cache',
            'prestashop.core.localization.locale.repository' => 'PrestaShop\\PrestaShop\\Core\\Localization\\Locale\\Repository',
        ];
    }

    public function compile(): void
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled(): bool
    {
        return true;
    }

    public function getRemovedIds(): array
    {
        return [
            '.service_locator.zH65KBq' => true,
            'Doctrine\\Bundle\\DoctrineBundle\\Controller\\ProfilerController' => true,
            'Doctrine\\Bundle\\DoctrineBundle\\Dbal\\ManagerRegistryAwareConnectionProvider' => true,
            'Doctrine\\Common\\Persistence\\ManagerRegistry' => true,
            'Doctrine\\DBAL\\Connection' => true,
            'Doctrine\\DBAL\\Connection $defaultConnection' => true,
            'Doctrine\\DBAL\\Driver\\Connection' => true,
            'Doctrine\\DBAL\\Tools\\Console\\Command\\RunSqlCommand' => true,
            'Doctrine\\ORM\\EntityManagerInterface' => true,
            'Doctrine\\ORM\\EntityManagerInterface $defaultEntityManager' => true,
            'Doctrine\\Persistence\\ManagerRegistry' => true,
            'PrestaShopBundle\\DependencyInjection\\CacheAdapterFactory' => true,
            'PrestaShopBundle\\DependencyInjection\\RuntimeConstEnvVarProcessor' => true,
            'PrestaShopCorp\\Billing\\Wrappers\\BillingContextWrapper' => true,
            'Psr\\Container\\ContainerInterface' => true,
            'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
            'data_collector.doctrine' => true,
            'doctrine.cache_clear_metadata_command' => true,
            'doctrine.cache_clear_query_cache_command' => true,
            'doctrine.cache_clear_result_command' => true,
            'doctrine.cache_collection_region_command' => true,
            'doctrine.clear_entity_region_command' => true,
            'doctrine.clear_query_region_command' => true,
            'doctrine.database_create_command' => true,
            'doctrine.database_drop_command' => true,
            'doctrine.database_import_command' => true,
            'doctrine.dbal.connection' => true,
            'doctrine.dbal.connection.configuration' => true,
            'doctrine.dbal.connection.event_manager' => true,
            'doctrine.dbal.connection_factory' => true,
            'doctrine.dbal.default_connection.configuration' => true,
            'doctrine.dbal.default_connection.event_manager' => true,
            'doctrine.dbal.event_manager' => true,
            'doctrine.dbal.logger' => true,
            'doctrine.dbal.logger.backtrace' => true,
            'doctrine.dbal.logger.chain' => true,
            'doctrine.dbal.logger.profiling' => true,
            'doctrine.dbal.schema_asset_filter_manager' => true,
            'doctrine.dbal.well_known_schema_asset_filter' => true,
            'doctrine.ensure_production_settings_command' => true,
            'doctrine.mapping_convert_command' => true,
            'doctrine.mapping_import_command' => true,
            'doctrine.mapping_info_command' => true,
            'doctrine.orm.configuration' => true,
            'doctrine.orm.container_repository_factory' => true,
            'doctrine.orm.default_annotation_metadata_driver' => true,
            'doctrine.orm.default_configuration' => true,
            'doctrine.orm.default_entity_listener_resolver' => true,
            'doctrine.orm.default_entity_manager.event_manager' => true,
            'doctrine.orm.default_entity_manager.property_info_extractor' => true,
            'doctrine.orm.default_entity_manager.validator_loader' => true,
            'doctrine.orm.default_listeners.attach_entity_listeners' => true,
            'doctrine.orm.default_manager_configurator' => true,
            'doctrine.orm.default_metadata_cache' => true,
            'doctrine.orm.default_metadata_driver' => true,
            'doctrine.orm.default_query_cache' => true,
            'doctrine.orm.default_result_cache' => true,
            'doctrine.orm.entity_manager.abstract' => true,
            'doctrine.orm.listeners.resolve_target_entity' => true,
            'doctrine.orm.manager_configurator.abstract' => true,
            'doctrine.orm.messenger.event_subscriber.doctrine_clear_entity_manager' => true,
            'doctrine.orm.metadata.annotation_reader' => true,
            'doctrine.orm.naming_strategy.default' => true,
            'doctrine.orm.naming_strategy.underscore' => true,
            'doctrine.orm.naming_strategy.underscore_number_aware' => true,
            'doctrine.orm.proxy_cache_warmer' => true,
            'doctrine.orm.quote_strategy.ansi' => true,
            'doctrine.orm.quote_strategy.default' => true,
            'doctrine.orm.security.user.provider' => true,
            'doctrine.orm.validator.unique' => true,
            'doctrine.orm.validator_initializer' => true,
            'doctrine.query_dql_command' => true,
            'doctrine.query_sql_command' => true,
            'doctrine.schema_create_command' => true,
            'doctrine.schema_drop_command' => true,
            'doctrine.schema_update_command' => true,
            'doctrine.schema_validate_command' => true,
            'doctrine.twig.doctrine_extension' => true,
            'form.type.entity' => true,
            'form.type_guesser.doctrine' => true,
            'messenger.middleware.doctrine_close_connection' => true,
            'messenger.middleware.doctrine_open_transaction_logger' => true,
            'messenger.middleware.doctrine_ping_connection' => true,
            'messenger.middleware.doctrine_transaction' => true,
            'messenger.transport.doctrine.factory' => true,
        ];
    }

    /**
     * Gets the public 'Monolog\Handler\HandlerInterface' shared service.
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function getHandlerInterfaceService()
    {
        return $this->services['Monolog\\Handler\\HandlerInterface'] = ($this->services['PsCheckout\\Infrastructure\\Logger\\LoggerHandlerFactory'] ?? $this->getLoggerHandlerFactoryService())->build();
    }

    /**
     * Gets the public 'PrestaShopCorp\Billing\Presenter\BillingPresenter' shared service.
     *
     * @return \PrestaShopCorp\Billing\Presenter\BillingPresenter
     */
    protected function getBillingPresenterService()
    {
        return $this->services['PrestaShopCorp\\Billing\\Presenter\\BillingPresenter'] = new \PrestaShopCorp\Billing\Presenter\BillingPresenter(($this->privates['PrestaShopCorp\\Billing\\Wrappers\\BillingContextWrapper'] ?? $this->getBillingContextWrapperService()), ($this->services['ps_facebook'] ?? $this->getPsFacebookService()));
    }

    /**
     * Gets the public 'PrestaShopCorp\Billing\Services\BillingService' shared service.
     *
     * @return \PrestaShopCorp\Billing\Services\BillingService
     */
    protected function getBillingServiceService()
    {
        return $this->services['PrestaShopCorp\\Billing\\Services\\BillingService'] = new \PrestaShopCorp\Billing\Services\BillingService(($this->privates['PrestaShopCorp\\Billing\\Wrappers\\BillingContextWrapper'] ?? $this->getBillingContextWrapperService()), ($this->services['ps_facebook'] ?? $this->getPsFacebookService()));
    }

    /**
     * Gets the public 'PrestaShop\ModuleLibCacheDirectoryProvider\Cache\CacheDirectoryProvider' shared service.
     *
     * @return \PrestaShop\ModuleLibCacheDirectoryProvider\Cache\CacheDirectoryProvider
     */
    protected function getCacheDirectoryProviderService()
    {
        return $this->services['PrestaShop\\ModuleLibCacheDirectoryProvider\\Cache\\CacheDirectoryProvider'] = new \PrestaShop\ModuleLibCacheDirectoryProvider\Cache\CacheDirectoryProvider('8.2.6', '/var/www/html', false);
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\API\Client\FacebookCategoryClient' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\API\Client\FacebookCategoryClient
     */
    protected function getFacebookCategoryClientService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookCategoryClient'] = new \PrestaShop\Module\PrestashopFacebook\API\Client\FacebookCategoryClient(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Factory\\PsApiClientFactory'] ?? $this->getPsApiClientFactoryService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\GoogleCategoryRepository'] ?? $this->getGoogleCategoryRepositoryService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\API\Client\FacebookClient' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\API\Client\FacebookClient
     */
    protected function getFacebookClientService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookClient'] = new \PrestaShop\Module\PrestashopFacebook\API\Client\FacebookClient(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Factory\\FacebookEssentialsApiClientFactory'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Factory\\FacebookEssentialsApiClientFactory'] = new \PrestaShop\Module\PrestashopFacebook\Factory\FacebookEssentialsApiClientFactory())), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\AccessTokenProvider'] ?? $this->getAccessTokenProviderService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\ConfigurationHandler'] ?? $this->getConfigurationHandlerService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\API\EventSubscriber\AccountSuspendedSubscriber' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\API\EventSubscriber\AccountSuspendedSubscriber
     */
    protected function getAccountSuspendedSubscriberService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\EventSubscriber\\AccountSuspendedSubscriber'] = new \PrestaShop\Module\PrestashopFacebook\API\EventSubscriber\AccountSuspendedSubscriber(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\API\EventSubscriber\ApiErrorSubscriber' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\API\EventSubscriber\ApiErrorSubscriber
     */
    protected function getApiErrorSubscriberService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\EventSubscriber\\ApiErrorSubscriber'] = new \PrestaShop\Module\PrestashopFacebook\API\EventSubscriber\ApiErrorSubscriber();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Adapter\ConfigurationAdapter' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Adapter\ConfigurationAdapter
     */
    protected function getConfigurationAdapterService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] = new \PrestaShop\Module\PrestashopFacebook\Adapter\ConfigurationAdapter(($this->services['ps_facebook.shop'] ?? $this->getPsFacebook_ShopService())->id);
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Adapter\ToolsAdapter' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Adapter\ToolsAdapter
     */
    protected function getToolsAdapterService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ToolsAdapter'] = new \PrestaShop\Module\PrestashopFacebook\Adapter\ToolsAdapter();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Buffer\TemplateBuffer' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Buffer\TemplateBuffer
     */
    protected function getTemplateBufferService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Buffer\\TemplateBuffer'] = new \PrestaShop\Module\PrestashopFacebook\Buffer\TemplateBuffer();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Config\Env' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Config\Env
     */
    protected function getEnvService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Config\\Env'] = new \PrestaShop\Module\PrestashopFacebook\Config\Env();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Dispatcher\EventDispatcher' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Dispatcher\EventDispatcher
     */
    protected function getEventDispatcherService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Dispatcher\\EventDispatcher'] = new \PrestaShop\Module\PrestashopFacebook\Dispatcher\EventDispatcher(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\ApiConversionHandler'] ?? $this->getApiConversionHandlerService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\PixelHandler'] ?? $this->getPixelHandlerService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\EventDataProvider'] ?? $this->getEventDataProviderService()), ($this->services['ps_facebook.context'] ?? $this->getPsFacebook_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Factory\FacebookEssentialsApiClientFactory' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Factory\FacebookEssentialsApiClientFactory
     */
    protected function getFacebookEssentialsApiClientFactoryService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Factory\\FacebookEssentialsApiClientFactory'] = new \PrestaShop\Module\PrestashopFacebook\Factory\FacebookEssentialsApiClientFactory();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Factory\PsApiClientFactory' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Factory\PsApiClientFactory
     */
    protected function getPsApiClientFactoryService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Factory\\PsApiClientFactory'] = new \PrestaShop\Module\PrestashopFacebook\Factory\PsApiClientFactory(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Config\\Env'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Config\\Env'] = new \PrestaShop\Module\PrestashopFacebook\Config\Env())), ($this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Facade\\PsAccounts'] ?? $this->getPsAccountsService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\ApiConversionHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\ApiConversionHandler
     */
    protected function getApiConversionHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\ApiConversionHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\ApiConversionHandler(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\ErrorHandler\\ErrorHandler'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\ErrorHandler\\ErrorHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\ErrorHandler\ErrorHandler())), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookClient'] ?? $this->getFacebookClientService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\CategoryMatchHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\CategoryMatchHandler
     */
    protected function getCategoryMatchHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\CategoryMatchHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\CategoryMatchHandler(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\GoogleCategoryRepository'] ?? $this->getGoogleCategoryRepositoryService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\ConfigurationHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\ConfigurationHandler
     */
    protected function getConfigurationHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\ConfigurationHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\ConfigurationHandler(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\ErrorHandler\ErrorHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\ErrorHandler\ErrorHandler
     */
    protected function getErrorHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\ErrorHandler\\ErrorHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\ErrorHandler\ErrorHandler();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\EventBusProductHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\EventBusProductHandler
     */
    protected function getEventBusProductHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\EventBusProductHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\EventBusProductHandler(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\ProductRepository())));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\MessengerHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\MessengerHandler
     */
    protected function getMessengerHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\MessengerHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\MessengerHandler(($this->services['ps_facebook.language'] ?? $this->getPsFacebook_LanguageService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Config\\Env'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Config\\Env'] = new \PrestaShop\Module\PrestashopFacebook\Config\Env())));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\PixelHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\PixelHandler
     */
    protected function getPixelHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\PixelHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\PixelHandler(($this->services['ps_facebook'] ?? $this->getPsFacebookService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Handler\PrevalidationScanRefreshHandler' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Handler\PrevalidationScanRefreshHandler
     */
    protected function getPrevalidationScanRefreshHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Handler\\PrevalidationScanRefreshHandler'] = new \PrestaShop\Module\PrestashopFacebook\Handler\PrevalidationScanRefreshHandler(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\PrevalidationScanCacheProvider'] ?? $this->getPrevalidationScanCacheProviderService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\ProductRepository())), ($this->services['ps_facebook.shop'] ?? $this->getPsFacebook_ShopService())->id);
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Manager\FbeFeatureManager' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Manager\FbeFeatureManager
     */
    protected function getFbeFeatureManagerService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Manager\\FbeFeatureManager'] = new \PrestaShop\Module\PrestashopFacebook\Manager\FbeFeatureManager(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookClient'] ?? $this->getFacebookClientService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Presenter\ModuleUpgradePresenter' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Presenter\ModuleUpgradePresenter
     */
    protected function getModuleUpgradePresenterService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Presenter\\ModuleUpgradePresenter'] = new \PrestaShop\Module\PrestashopFacebook\Presenter\ModuleUpgradePresenter(($this->services['ps_facebook.context'] ?? $this->getPsFacebook_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\AccessTokenProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\AccessTokenProvider
     */
    protected function getAccessTokenProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\AccessTokenProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\AccessTokenProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['ps_facebook.controller'] ?? $this->getPsFacebook_ControllerService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Factory\\PsApiClientFactory'] ?? $this->getPsApiClientFactoryService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\EventDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\EventDataProvider
     */
    protected function getEventDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\EventDataProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\EventDataProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ToolsAdapter'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ToolsAdapter'] = new \PrestaShop\Module\PrestashopFacebook\Adapter\ToolsAdapter())), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\ProductRepository())), ($this->services['ps_facebook.context'] ?? $this->getPsFacebook_ContextService()), ($this->services['ps_facebook'] ?? $this->getPsFacebookService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductAvailabilityProvider'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductAvailabilityProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\ProductAvailabilityProvider())), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\GoogleCategoryRepository'] ?? $this->getGoogleCategoryRepositoryService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\GoogleCategoryProvider'] ?? $this->getGoogleCategoryProviderService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\FacebookDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\FacebookDataProvider
     */
    protected function getFacebookDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\FacebookDataProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\FacebookDataProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookClient'] ?? $this->getFacebookClientService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\FbeDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\FbeDataProvider
     */
    protected function getFbeDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\FbeDataProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\FbeDataProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\FbeFeatureDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\FbeFeatureDataProvider
     */
    protected function getFbeFeatureDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\FbeFeatureDataProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\FbeFeatureDataProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\API\\Client\\FacebookClient'] ?? $this->getFacebookClientService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\GoogleCategoryProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\GoogleCategoryProvider
     */
    protected function getGoogleCategoryProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\GoogleCategoryProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\GoogleCategoryProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\GoogleCategoryRepository'] ?? $this->getGoogleCategoryRepositoryService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\MultishopDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\MultishopDataProvider
     */
    protected function getMultishopDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\MultishopDataProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\MultishopDataProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ShopRepository'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ShopRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\ShopRepository())), ($this->services['PrestaShop\\Module\\Ps_facebook\\Tracker\\Segment'] ?? $this->getSegmentService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\PrevalidationScanCacheProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\PrevalidationScanCacheProvider
     */
    protected function getPrevalidationScanCacheProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\PrevalidationScanCacheProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\PrevalidationScanCacheProvider(($this->services['ps_facebook'] ?? $this->getPsFacebookService()), ($this->services['ps_facebook.cache'] ?? $this->getPsFacebook_CacheService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\PrevalidationScanDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\PrevalidationScanDataProvider
     */
    protected function getPrevalidationScanDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\PrevalidationScanDataProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\PrevalidationScanDataProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\PrevalidationScanCacheProvider'] ?? $this->getPrevalidationScanCacheProviderService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\ProductAvailabilityProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\ProductAvailabilityProvider
     */
    protected function getProductAvailabilityProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductAvailabilityProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\ProductAvailabilityProvider();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Provider\ProductSyncReportProvider' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Provider\ProductSyncReportProvider
     */
    protected function getProductSyncReportProviderService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Provider\\ProductSyncReportProvider'] = new \PrestaShop\Module\PrestashopFacebook\Provider\ProductSyncReportProvider(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Factory\\PsApiClientFactory'] ?? $this->getPsApiClientFactoryService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Repository\GoogleCategoryRepository' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Repository\GoogleCategoryRepository
     */
    protected function getGoogleCategoryRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\GoogleCategoryRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\GoogleCategoryRepository(($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Repository\ProductRepository' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Repository\ProductRepository
     */
    protected function getProductRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ProductRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\ProductRepository();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Repository\ServerInformationRepository' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Repository\ServerInformationRepository
     */
    protected function getServerInformationRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ServerInformationRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\ServerInformationRepository(($this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Facade\\PsAccounts'] ?? $this->getPsAccountsService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Repository\ShopRepository' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Repository\ShopRepository
     */
    protected function getShopRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\ShopRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\ShopRepository();
    }

    /**
     * Gets the public 'PrestaShop\Module\PrestashopFacebook\Repository\TabRepository' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Repository\TabRepository
     */
    protected function getTabRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PrestashopFacebook\\Repository\\TabRepository'] = new \PrestaShop\Module\PrestashopFacebook\Repository\TabRepository();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsAccounts\Presenter\PsAccountsPresenter' shared service.
     *
     * @return \PrestaShop\Module\PsAccounts\Presenter\PsAccountsPresenter
     */
    protected function getPsAccountsPresenterService()
    {
        return $this->services['PrestaShop\\Module\\PsAccounts\\Presenter\\PsAccountsPresenter'] = \PrestaShop\Module\PsAccounts\ServiceProvider\StaticProvider::provide('PrestaShop\\Module\\PsAccounts\\Presenter\\PsAccountsPresenter');
    }

    /**
     * Gets the public 'PrestaShop\Module\PsAccounts\Repository\UserTokenRepository' shared service.
     *
     * @return \PrestaShop\Module\PsAccounts\Repository\UserTokenRepository
     */
    protected function getUserTokenRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsAccounts\\Repository\\UserTokenRepository'] = \PrestaShop\Module\PsAccounts\ServiceProvider\StaticProvider::provide('PrestaShop\\Module\\PsAccounts\\Repository\\UserTokenRepository');
    }

    /**
     * Gets the public 'PrestaShop\Module\PsAccounts\Service\PsAccountsService' shared service.
     *
     * @return \PrestaShop\Module\PsAccounts\Service\PsAccountsService
     */
    protected function getPsAccountsServiceService()
    {
        return $this->services['PrestaShop\\Module\\PsAccounts\\Service\\PsAccountsService'] = \PrestaShop\Module\PsAccounts\ServiceProvider\StaticProvider::provide('PrestaShop\\Module\\PsAccounts\\Service\\PsAccountsService');
    }

    /**
     * Gets the public 'PrestaShop\Module\PsAccounts\Service\PsBillingService' shared service.
     *
     * @return \PrestaShop\Module\PsAccounts\Service\PsBillingService
     */
    protected function getPsBillingServiceService()
    {
        return $this->services['PrestaShop\\Module\\PsAccounts\\Service\\PsBillingService'] = \PrestaShop\Module\PsAccounts\ServiceProvider\StaticProvider::provide('PrestaShop\\Module\\PsAccounts\\Service\\PsBillingService');
    }

    /**
     * Gets the public 'PrestaShop\Module\Ps_facebook\Tracker\Segment' shared service.
     *
     * @return \PrestaShop\Module\Ps_facebook\Tracker\Segment
     */
    protected function getSegmentService()
    {
        return $this->services['PrestaShop\\Module\\Ps_facebook\\Tracker\\Segment'] = new \PrestaShop\Module\Ps_facebook\Tracker\Segment(($this->services['ps_facebook.context'] ?? $this->getPsFacebook_ContextService()), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Config\\Env'] ?? ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Config\\Env'] = new \PrestaShop\Module\PrestashopFacebook\Config\Env())), ($this->services['PrestaShop\\Module\\PrestashopFacebook\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapterService()), ($this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Facade\\PsAccounts'] ?? $this->getPsAccountsService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Controller\Admin\PsshippingCarrierController' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingCarrierController
     */
    protected function getPsshippingCarrierControllerService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingCarrierController'] = new \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingCarrierController(($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Controller\Admin\PsshippingConfigurationController' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingConfigurationController
     */
    protected function getPsshippingConfigurationControllerService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingConfigurationController'] = new \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingConfigurationController(($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Controller\Admin\PsshippingFaqController' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingFaqController
     */
    protected function getPsshippingFaqControllerService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingFaqController'] = new \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingFaqController(($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Controller\Admin\PsshippingHomeController' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingHomeController
     */
    protected function getPsshippingHomeControllerService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingHomeController'] = new \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingHomeController(($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Controller\Admin\PsshippingKeycloakAuthController' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingKeycloakAuthController
     */
    protected function getPsshippingKeycloakAuthControllerService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingKeycloakAuthController'] = new \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingKeycloakAuthController();
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Controller\Admin\PsshippingOrdersController' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingOrdersController
     */
    protected function getPsshippingOrdersControllerService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Controller\\Admin\\PsshippingOrdersController'] = new \PrestaShop\Module\Psshipping\Controller\Admin\PsshippingOrdersController(($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Domain\Carriers\CarrierRepository' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Domain\Carriers\CarrierRepository
     */
    protected function getCarrierRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Domain\\Carriers\\CarrierRepository'] = new \PrestaShop\Module\Psshipping\Domain\Carriers\CarrierRepository(($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Domain\Carriers\PickupCarrierConfiguration' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Domain\Carriers\PickupCarrierConfiguration
     */
    protected function getPickupCarrierConfigurationService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Domain\\Carriers\\PickupCarrierConfiguration'] = new \PrestaShop\Module\Psshipping\Domain\Carriers\PickupCarrierConfiguration('prestashop.core.command_bus');
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Domain\Carriers\StandardCarrierConfiguration' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Domain\Carriers\StandardCarrierConfiguration
     */
    protected function getStandardCarrierConfigurationService()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Domain\\Carriers\\StandardCarrierConfiguration'] = new \PrestaShop\Module\Psshipping\Domain\Carriers\StandardCarrierConfiguration('prestashop.core.command_bus');
    }

    /**
     * Gets the public 'PrestaShop\Module\Psshipping\Handler\ErrorHandler' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Handler\ErrorHandler
     */
    protected function getErrorHandler2Service()
    {
        return $this->services['PrestaShop\\Module\\Psshipping\\Handler\\ErrorHandler'] = new \PrestaShop\Module\Psshipping\Handler\ErrorHandler();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Adapter\ConfigurationAdapter' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Adapter\ConfigurationAdapter
     */
    protected function getConfigurationAdapter2Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Adapter\\ConfigurationAdapter'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Adapter\ConfigurationAdapter(($this->services['psxmarketingwithgoogle.shop'] ?? $this->getPsxmarketingwithgoogle_ShopService())->id);
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Buffer\TemplateBuffer' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Buffer\TemplateBuffer
     */
    protected function getTemplateBuffer2Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Buffer\\TemplateBuffer'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Buffer\TemplateBuffer();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Config\Env' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Config\Env
     */
    protected function getEnv2Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Config\\Env'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Config\Env();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Conversion\EnhancedConversionToggle' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Conversion\EnhancedConversionToggle
     */
    protected function getEnhancedConversionToggleService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Conversion\\EnhancedConversionToggle'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Conversion\EnhancedConversionToggle(($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapter2Service()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Conversion\UserDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Conversion\UserDataProvider
     */
    protected function getUserDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Conversion\\UserDataProvider'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Conversion\UserDataProvider(($this->services['psxmarketingwithgoogle.customer'] ?? $this->getPsxmarketingwithgoogle_CustomerService()), ($this->services['psxmarketingwithgoogle.cart'] ?? $this->getPsxmarketingwithgoogle_CartService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Handler\ErrorHandler' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Handler\ErrorHandler
     */
    protected function getErrorHandler3Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Handler\\ErrorHandler'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Handler\ErrorHandler();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Handler\RemarketingHookHandler' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Handler\RemarketingHookHandler
     */
    protected function getRemarketingHookHandlerService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Handler\\RemarketingHookHandler'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Handler\RemarketingHookHandler(($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapter2Service()), ($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Buffer\\TemplateBuffer'] ?? ($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Buffer\\TemplateBuffer'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Buffer\TemplateBuffer())), ($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()), ($this->services['psxmarketingwithgoogle'] ?? $this->getPsxmarketingwithgoogleService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Provider\CartEventDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Provider\CartEventDataProvider
     */
    protected function getCartEventDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\CartEventDataProvider'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Provider\CartEventDataProvider(($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Provider\PageViewEventDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Provider\PageViewEventDataProvider
     */
    protected function getPageViewEventDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\PageViewEventDataProvider'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Provider\PageViewEventDataProvider(($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Provider\ProductDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Provider\ProductDataProvider
     */
    protected function getProductDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\ProductDataProvider'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Provider\ProductDataProvider(($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Provider\PurchaseEventDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Provider\PurchaseEventDataProvider
     */
    protected function getPurchaseEventDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\PurchaseEventDataProvider'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Provider\PurchaseEventDataProvider(($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\ProductDataProvider'] ?? $this->getProductDataProviderService()), ($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()), ($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapter2Service()), ($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\LanguageRepository'] ?? $this->getLanguageRepositoryService()), ($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CountryRepository'] ?? $this->getCountryRepositoryService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Provider\VerificationTagDataProvider' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Provider\VerificationTagDataProvider
     */
    protected function getVerificationTagDataProviderService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Provider\\VerificationTagDataProvider'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Provider\VerificationTagDataProvider(($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapter2Service()), ($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\VerificationTagRepository'] ?? $this->getVerificationTagRepositoryService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\AttributesRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\AttributesRepository
     */
    protected function getAttributesRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\AttributesRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\AttributesRepository(($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\CarrierRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CarrierRepository
     */
    protected function getCarrierRepository2Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CarrierRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CarrierRepository();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\CategoryRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CategoryRepository
     */
    protected function getCategoryRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CategoryRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CategoryRepository(($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\CountryRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CountryRepository
     */
    protected function getCountryRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CountryRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CountryRepository(($this->services['psxmarketingwithgoogle.db'] ?? $this->getPsxmarketingwithgoogle_DbService()), ($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()), ($this->services['psxmarketingwithgoogle.country'] ?? $this->getPsxmarketingwithgoogle_CountryService()), ($this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Adapter\\ConfigurationAdapter'] ?? $this->getConfigurationAdapter2Service()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\CurrencyRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CurrencyRepository
     */
    protected function getCurrencyRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\CurrencyRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\CurrencyRepository(($this->services['psxmarketingwithgoogle.currency'] ?? $this->getPsxmarketingwithgoogle_CurrencyService()), ($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\LanguageRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\LanguageRepository
     */
    protected function getLanguageRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\LanguageRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\LanguageRepository(($this->services['psxmarketingwithgoogle.shop'] ?? $this->getPsxmarketingwithgoogle_ShopService())->id);
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\ManufacturerRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\ManufacturerRepository
     */
    protected function getManufacturerRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\ManufacturerRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\ManufacturerRepository(($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\ProductRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\ProductRepository
     */
    protected function getProductRepository2Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\ProductRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\ProductRepository();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\StateRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\StateRepository
     */
    protected function getStateRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\StateRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\StateRepository(($this->services['psxmarketingwithgoogle.db'] ?? $this->getPsxmarketingwithgoogle_DbService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\TabRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\TabRepository
     */
    protected function getTabRepository2Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\TabRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\TabRepository();
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\TaxRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\TaxRepository
     */
    protected function getTaxRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\TaxRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\TaxRepository(($this->services['psxmarketingwithgoogle.db'] ?? $this->getPsxmarketingwithgoogle_DbService()), ($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Repository\VerificationTagRepository' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Repository\VerificationTagRepository
     */
    protected function getVerificationTagRepositoryService()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Repository\\VerificationTagRepository'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Repository\VerificationTagRepository(($this->services['psxmarketingwithgoogle.db'] ?? $this->getPsxmarketingwithgoogle_DbService()), ($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\Module\PsxMarketingWithGoogle\Tracker\Segment' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Tracker\Segment
     */
    protected function getSegment2Service()
    {
        return $this->services['PrestaShop\\Module\\PsxMarketingWithGoogle\\Tracker\\Segment'] = new \PrestaShop\Module\PsxMarketingWithGoogle\Tracker\Segment(($this->services['psxmarketingwithgoogle.context'] ?? $this->getPsxmarketingwithgoogle_ContextService()));
    }

    /**
     * Gets the public 'PrestaShop\PrestaShop\Adapter\Configuration' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Configuration
     */
    protected function getConfigurationService()
    {
        return $this->services['PrestaShop\\PrestaShop\\Adapter\\Configuration'] = new \PrestaShop\PrestaShop\Adapter\Configuration();
    }

    /**
     * Gets the public 'PrestaShop\PrestaShop\Adapter\ContextStateManager' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\ContextStateManager
     */
    protected function getContextStateManagerService()
    {
        return $this->services['PrestaShop\\PrestaShop\\Adapter\\ContextStateManager'] = new \PrestaShop\PrestaShop\Adapter\ContextStateManager(($this->services['PrestaShop\\PrestaShop\\Adapter\\LegacyContext'] ?? $this->getLegacyContextService()));
    }

    /**
     * Gets the public 'PrestaShop\PrestaShop\Adapter\Currency\CurrencyDataProvider' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Currency\CurrencyDataProvider
     */
    protected function getCurrencyDataProviderService()
    {
        return $this->services['PrestaShop\\PrestaShop\\Adapter\\Currency\\CurrencyDataProvider'] = new \PrestaShop\PrestaShop\Adapter\Currency\CurrencyDataProvider(($this->services['PrestaShop\\PrestaShop\\Adapter\\Configuration'] ?? ($this->services['PrestaShop\\PrestaShop\\Adapter\\Configuration'] = new \PrestaShop\PrestaShop\Adapter\Configuration())), ((($this->services['PrestaShop\\PrestaShop\\Adapter\\LegacyContext'] ?? $this->getLegacyContextService())->getContext()->shop) ? (($this->services['PrestaShop\\PrestaShop\\Adapter\\LegacyContext'] ?? $this->getLegacyContextService())->getContext()->shop->id) : (null)));
    }

    /**
     * Gets the public 'PrestaShop\PrestaShop\Adapter\LegacyContext' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\LegacyContext
     */
    protected function getLegacyContextService()
    {
        return $this->services['PrestaShop\\PrestaShop\\Adapter\\LegacyContext'] = new \PrestaShop\PrestaShop\Adapter\LegacyContext('/mails/themes', ($this->services['PrestaShop\\PrestaShop\\Adapter\\Tools'] ?? ($this->services['PrestaShop\\PrestaShop\\Adapter\\Tools'] = new \PrestaShop\PrestaShop\Adapter\Tools())));
    }

    /**
     * Gets the public 'PrestaShop\PrestaShop\Adapter\Tools' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Tools
     */
    protected function getToolsService()
    {
        return $this->services['PrestaShop\\PrestaShop\\Adapter\\Tools'] = new \PrestaShop\PrestaShop\Adapter\Tools();
    }

    /**
     * Gets the public 'PrestaShop\PrestaShop\Core\Hook\HookModuleFilter' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Hook\HookModuleFilter
     */
    protected function getHookModuleFilterService()
    {
        return $this->services['PrestaShop\\PrestaShop\\Core\\Hook\\HookModuleFilter'] = new \PrestaShop\PrestaShop\Core\Hook\HookModuleFilter(new RewindableGenerator(function () {
            return new \EmptyIterator();
        }, 0));
    }

    /**
     * Gets the public 'PrestaShop\PrestaShop\Core\Localization\Locale\Repository' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Locale\Repository
     */
    protected function getRepositoryService()
    {
        return $this->services['PrestaShop\\PrestaShop\\Core\\Localization\\Locale\\Repository'] = new \PrestaShop\PrestaShop\Core\Localization\Locale\Repository(($this->services['prestashop.core.localization.cldr.locale_repository'] ?? $this->getPrestashop_Core_Localization_Cldr_LocaleRepositoryService()), ($this->services['prestashop.core.localization.currency.repository'] ?? $this->getPrestashop_Core_Localization_Currency_RepositoryService()));
    }

    /**
     * Gets the public 'PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts' shared service.
     *
     * @return \PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts
     */
    protected function getPsAccountsService()
    {
        return $this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Facade\\PsAccounts'] = new \PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts(($this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Installer'] ?? ($this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Installer'] = new \PrestaShop\PsAccountsInstaller\Installer\Installer('3.0.0'))));
    }

    /**
     * Gets the public 'PrestaShop\PsAccountsInstaller\Installer\Installer' shared service.
     *
     * @return \PrestaShop\PsAccountsInstaller\Installer\Installer
     */
    protected function getInstallerService()
    {
        return $this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Installer'] = new \PrestaShop\PsAccountsInstaller\Installer\Installer('3.0.0');
    }

    /**
     * Gets the public 'PsCheckout\Api\Http\CheckoutHttpClient' shared service.
     *
     * @return \PsCheckout\Api\Http\CheckoutHttpClient
     */
    protected function getCheckoutHttpClientService()
    {
        return $this->services['PsCheckout\\Api\\Http\\CheckoutHttpClient'] = new \PsCheckout\Api\Http\CheckoutHttpClient(($this->services['PsCheckout\\Api\\Http\\Configuration\\CheckoutClientConfigurationBuilder'] ?? $this->getCheckoutClientConfigurationBuilderService()));
    }

    /**
     * Gets the public 'PsCheckout\Api\Http\Configuration\CheckoutClientConfigurationBuilder' shared service.
     *
     * @return \PsCheckout\Api\Http\Configuration\CheckoutClientConfigurationBuilder
     */
    protected function getCheckoutClientConfigurationBuilderService()
    {
        return $this->services['PsCheckout\\Api\\Http\\Configuration\\CheckoutClientConfigurationBuilder'] = new \PsCheckout\Api\Http\Configuration\CheckoutClientConfigurationBuilder(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->version, ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Link'] ?? $this->getLinkService()), ($this->services['PsCheckout\\Infrastructure\\Environment\\Env'] ?? $this->getEnv3Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PsAccountRepository'] ?? $this->getPsAccountRepositoryService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Api\Http\Configuration\OrderHttpClientConfigurationBuilder' shared service.
     *
     * @return \PsCheckout\Api\Http\Configuration\OrderHttpClientConfigurationBuilder
     */
    protected function getOrderHttpClientConfigurationBuilderService()
    {
        return $this->services['PsCheckout\\Api\\Http\\Configuration\\OrderHttpClientConfigurationBuilder'] = new \PsCheckout\Api\Http\Configuration\OrderHttpClientConfigurationBuilder(($this->services['PsCheckout\\Infrastructure\\Environment\\Env'] ?? $this->getEnv3Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PsAccountRepository'] ?? $this->getPsAccountRepositoryService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Link'] ?? $this->getLinkService()), ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->version);
    }

    /**
     * Gets the public 'PsCheckout\Api\Http\Configuration\OrderShipmentTrackingConfigurationBuilder' shared service.
     *
     * @return \PsCheckout\Api\Http\Configuration\OrderShipmentTrackingConfigurationBuilder
     */
    protected function getOrderShipmentTrackingConfigurationBuilderService()
    {
        return $this->services['PsCheckout\\Api\\Http\\Configuration\\OrderShipmentTrackingConfigurationBuilder'] = new \PsCheckout\Api\Http\Configuration\OrderShipmentTrackingConfigurationBuilder(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->version, ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Link'] ?? $this->getLinkService()), ($this->services['PsCheckout\\Infrastructure\\Environment\\Env'] ?? $this->getEnv3Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PsAccountRepository'] ?? $this->getPsAccountRepositoryService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Api\Http\OrderHttpClient' shared service.
     *
     * @return \PsCheckout\Api\Http\OrderHttpClient
     */
    protected function getOrderHttpClientService()
    {
        return $this->services['PsCheckout\\Api\\Http\\OrderHttpClient'] = new \PsCheckout\Api\Http\OrderHttpClient(($this->services['PsCheckout\\Api\\Http\\Configuration\\OrderHttpClientConfigurationBuilder'] ?? $this->getOrderHttpClientConfigurationBuilderService()));
    }

    /**
     * Gets the public 'PsCheckout\Api\Http\OrderShipmentTrackingHttpClient' shared service.
     *
     * @return \PsCheckout\Api\Http\OrderShipmentTrackingHttpClient
     */
    protected function getOrderShipmentTrackingHttpClientService()
    {
        return $this->services['PsCheckout\\Api\\Http\\OrderShipmentTrackingHttpClient'] = new \PsCheckout\Api\Http\OrderShipmentTrackingHttpClient(($this->services['PsCheckout\\Api\\Http\\Configuration\\OrderShipmentTrackingConfigurationBuilder'] ?? $this->getOrderShipmentTrackingConfigurationBuilderService()));
    }

    /**
     * Gets the public 'PsCheckout\Cache\Array\PayPalOrder' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\ArrayAdapter
     */
    protected function getPayPalOrderService()
    {
        return $this->services['PsCheckout\\Cache\\Array\\PayPalOrder'] = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
    }

    /**
     * Gets the public 'PsCheckout\Cache\Array\ShippingTracking' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\ArrayAdapter
     */
    protected function getShippingTrackingService()
    {
        return $this->services['PsCheckout\\Cache\\Array\\ShippingTracking'] = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
    }

    /**
     * Gets the public 'PsCheckout\Cache\FileSystem\PayPalOrder' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\FilesystemAdapter
     */
    protected function getPayPalOrder2Service()
    {
        return $this->services['PsCheckout\\Cache\\FileSystem\\PayPalOrder'] = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('paypal-orders', 3600, ($this->services['PrestaShop\\ModuleLibCacheDirectoryProvider\\Cache\\CacheDirectoryProvider'] ?? ($this->services['PrestaShop\\ModuleLibCacheDirectoryProvider\\Cache\\CacheDirectoryProvider'] = new \PrestaShop\ModuleLibCacheDirectoryProvider\Cache\CacheDirectoryProvider('8.2.6', '/var/www/html', false)))->getPath());
    }

    /**
     * Gets the public 'PsCheckout\Cache\FileSystem\ShippingTracking' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\FilesystemAdapter
     */
    protected function getShippingTracking2Service()
    {
        return $this->services['PsCheckout\\Cache\\FileSystem\\ShippingTracking'] = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('shipping-tracking', 3600, ($this->services['PrestaShop\\ModuleLibCacheDirectoryProvider\\Cache\\CacheDirectoryProvider'] ?? ($this->services['PrestaShop\\ModuleLibCacheDirectoryProvider\\Cache\\CacheDirectoryProvider'] = new \PrestaShop\ModuleLibCacheDirectoryProvider\Cache\CacheDirectoryProvider('8.2.6', '/var/www/html', false)))->getPath());
    }

    /**
     * Gets the public 'PsCheckout\Core\Customer\Action\ExpressCheckoutAction' shared service.
     *
     * @return \PsCheckout\Core\Customer\Action\ExpressCheckoutAction
     */
    protected function getExpressCheckoutActionService()
    {
        return $this->services['PsCheckout\\Core\\Customer\\Action\\ExpressCheckoutAction'] = new \PsCheckout\Core\Customer\Action\ExpressCheckoutAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Action\\CustomerAuthenticationAction'] ?? $this->getCustomerAuthenticationActionService()), ($this->services['PsCheckout\\Infrastructure\\Action\\CreateOrUpdateAddressAction'] ?? $this->getCreateOrUpdateAddressActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\FundingSource\Factory\FundingSourceTokenFactory' shared service.
     *
     * @return \PsCheckout\Core\FundingSource\Factory\FundingSourceTokenFactory
     */
    protected function getFundingSourceTokenFactoryService()
    {
        return $this->services['PsCheckout\\Core\\FundingSource\\Factory\\FundingSourceTokenFactory'] = new \PsCheckout\Core\FundingSource\Factory\FundingSourceTokenFactory(($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider'] ?? $this->getFundingSourceTranslationProviderService()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\LogoPresenter'] ?? $this->getLogoPresenterService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\OrderState\Action\ChangeOrderStateAction' shared service.
     *
     * @return \PsCheckout\Core\OrderState\Action\ChangeOrderStateAction
     */
    protected function getChangeOrderStateActionService()
    {
        return $this->services['PsCheckout\\Core\\OrderState\\Action\\ChangeOrderStateAction'] = new \PsCheckout\Core\OrderState\Action\ChangeOrderStateAction(($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderStateRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderStateRepository'] = new \PsCheckout\Infrastructure\Repository\OrderStateRepository())), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderHistoryRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderHistoryRepository'] = new \PsCheckout\Infrastructure\Repository\OrderHistoryRepository())));
    }

    /**
     * Gets the public 'PsCheckout\Core\OrderState\Action\SetCompletedOrderStateAction' shared service.
     *
     * @return \PsCheckout\Core\OrderState\Action\SetCompletedOrderStateAction
     */
    protected function getSetCompletedOrderStateActionService()
    {
        return $this->services['PsCheckout\\Core\\OrderState\\Action\\SetCompletedOrderStateAction'] = new \PsCheckout\Core\OrderState\Action\SetCompletedOrderStateAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Core\\Order\\Validator\\OrderAmountValidator'] ?? ($this->services['PsCheckout\\Core\\Order\\Validator\\OrderAmountValidator'] = new \PsCheckout\Core\Order\Validator\OrderAmountValidator())), ($this->services['PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper'] ?? $this->getOrderStateMapperService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\ChangeOrderStateAction'] ?? $this->getChangeOrderStateActionService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\OrderState\Action\SetDeclinedOrderStateAction' shared service.
     *
     * @return \PsCheckout\Core\OrderState\Action\SetDeclinedOrderStateAction
     */
    protected function getSetDeclinedOrderStateActionService()
    {
        return $this->services['PsCheckout\\Core\\OrderState\\Action\\SetDeclinedOrderStateAction'] = new \PsCheckout\Core\OrderState\Action\SetDeclinedOrderStateAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper'] ?? $this->getOrderStateMapperService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\ChangeOrderStateAction'] ?? $this->getChangeOrderStateActionService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\OrderState\Action\SetPendingOrderStateAction' shared service.
     *
     * @return \PsCheckout\Core\OrderState\Action\SetPendingOrderStateAction
     */
    protected function getSetPendingOrderStateActionService()
    {
        return $this->services['PsCheckout\\Core\\OrderState\\Action\\SetPendingOrderStateAction'] = new \PsCheckout\Core\OrderState\Action\SetPendingOrderStateAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper'] ?? $this->getOrderStateMapperService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\ChangeOrderStateAction'] ?? $this->getChangeOrderStateActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\OrderState\Action\SetRefundedOrderStateAction' shared service.
     *
     * @return \PsCheckout\Core\OrderState\Action\SetRefundedOrderStateAction
     */
    protected function getSetRefundedOrderStateActionService()
    {
        return $this->services['PsCheckout\\Core\\OrderState\\Action\\SetRefundedOrderStateAction'] = new \PsCheckout\Core\OrderState\Action\SetRefundedOrderStateAction(($this->services['PsCheckout\\Core\\PayPal\\Refund\\Provider\\PayPalRefundOrderProvider'] ?? $this->getPayPalRefundOrderProviderService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()), ($this->services['PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper'] ?? $this->getOrderStateMapperService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\ChangeOrderStateAction'] ?? $this->getChangeOrderStateActionService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache'] ?? $this->getPayPalOrderCacheService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\OrderState\Action\SetReversedOrderStateAction' shared service.
     *
     * @return \PsCheckout\Core\OrderState\Action\SetReversedOrderStateAction
     */
    protected function getSetReversedOrderStateActionService()
    {
        return $this->services['PsCheckout\\Core\\OrderState\\Action\\SetReversedOrderStateAction'] = new \PsCheckout\Core\OrderState\Action\SetReversedOrderStateAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper'] ?? $this->getOrderStateMapperService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\ChangeOrderStateAction'] ?? $this->getChangeOrderStateActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\OrderState\Service\OrderStateMapper' shared service.
     *
     * @return \PsCheckout\Core\OrderState\Service\OrderStateMapper
     */
    protected function getOrderStateMapperService()
    {
        return $this->services['PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper'] = new \PsCheckout\Core\OrderState\Service\OrderStateMapper(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Action\CreateOrderAction' shared service.
     *
     * @return \PsCheckout\Core\Order\Action\CreateOrderAction
     */
    protected function getCreateOrderActionService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Action\\CreateOrderAction'] = new \PsCheckout\Core\Order\Action\CreateOrderAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Core\\Order\\Action\\CreateValidateOrderDataAction'] ?? $this->getCreateValidateOrderDataActionService()), ($this->services['PsCheckout\\Core\\Order\\Action\\ValidateOrderAction'] ?? $this->getValidateOrderActionService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderMatrixRepository'] ?? $this->getPayPalOrderMatrixRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Action\CreateOrderPaymentAction' shared service.
     *
     * @return \PsCheckout\Core\Order\Action\CreateOrderPaymentAction
     */
    protected function getCreateOrderPaymentActionService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Action\\CreateOrderPaymentAction'] = new \PsCheckout\Core\Order\Action\CreateOrderPaymentAction(($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider'] ?? $this->getFundingSourceTranslationProviderService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Currency'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Currency'] = new \PsCheckout\Infrastructure\Adapter\Currency())));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Action\CreateValidateOrderDataAction' shared service.
     *
     * @return \PsCheckout\Core\Order\Action\CreateValidateOrderDataAction
     */
    protected function getCreateValidateOrderDataActionService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Action\\CreateValidateOrderDataAction'] = new \PsCheckout\Core\Order\Action\CreateValidateOrderDataAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Core\\OrderState\\Service\\OrderStateMapper'] ?? $this->getOrderStateMapperService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\CurrencyRepository'] ?? $this->getCurrencyRepository2Service()), ($this->services['PsCheckout\\Core\\Order\\Validator\\OrderAmountValidator'] ?? ($this->services['PsCheckout\\Core\\Order\\Validator\\OrderAmountValidator'] = new \PsCheckout\Core\Order\Validator\OrderAmountValidator())), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Action\ValidateOrderAction' shared service.
     *
     * @return \PsCheckout\Core\Order\Action\ValidateOrderAction
     */
    protected function getValidateOrderActionService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Action\\ValidateOrderAction'] = new \PsCheckout\Core\Order\Action\ValidateOrderAction(($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider'] ?? $this->getFundingSourceTranslationProviderService()), ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\AmountBreakdownNode' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\AmountBreakdownNode
     */
    protected function getAmountBreakdownNodeService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\AmountBreakdownNode'] = new \PsCheckout\Core\Order\Builder\Node\AmountBreakdownNode();
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\ApplicationContextNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\ApplicationContextNodeBuilder
     */
    protected function getApplicationContextNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\ApplicationContextNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\ApplicationContextNodeBuilder(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Link'] ?? $this->getLinkService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\BaseNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\BaseNodeBuilder
     */
    protected function getBaseNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\BaseNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\BaseNodeBuilder(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\CardPaymentSourceNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\CardPaymentSourceNodeBuilder
     */
    protected function getCardPaymentSourceNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\CardPaymentSourceNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\CardPaymentSourceNodeBuilder(($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalConfiguration'] ?? $this->getPayPalConfigurationService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\CountryRepository'] ?? $this->getCountryRepository2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] = new \PsCheckout\Infrastructure\Repository\StateRepository())));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\GooglePayPaymentSourceNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\GooglePayPaymentSourceNodeBuilder
     */
    protected function getGooglePayPaymentSourceNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\GooglePayPaymentSourceNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\GooglePayPaymentSourceNodeBuilder(($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalConfiguration'] ?? $this->getPayPalConfigurationService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\PayPalPaymentSourceNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\PayPalPaymentSourceNodeBuilder
     */
    protected function getPayPalPaymentSourceNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PayPalPaymentSourceNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\PayPalPaymentSourceNodeBuilder();
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\PayerNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\PayerNodeBuilder
     */
    protected function getPayerNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PayerNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\PayerNodeBuilder(($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Validate'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Validate'] = new \PsCheckout\Infrastructure\Adapter\Validate())), ($this->services['PsCheckout\\Infrastructure\\Repository\\CountryRepository'] ?? $this->getCountryRepository2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] = new \PsCheckout\Infrastructure\Repository\StateRepository())));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\PaymentSource\VenmoPaymentSourceNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\PaymentSource\VenmoPaymentSourceNodeBuilder
     */
    protected function getVenmoPaymentSourceNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PaymentSource\\VenmoPaymentSourceNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\PaymentSource\VenmoPaymentSourceNodeBuilder();
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\PuiPaymentSourceNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\PuiPaymentSourceNodeBuilder
     */
    protected function getPuiPaymentSourceNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PuiPaymentSourceNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\PuiPaymentSourceNodeBuilder(($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Validate'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Validate'] = new \PsCheckout\Infrastructure\Adapter\Validate())), ($this->services['PsCheckout\\Infrastructure\\Repository\\CountryRepository'] ?? $this->getCountryRepository2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\ShippingNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\ShippingNodeBuilder
     */
    protected function getShippingNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\ShippingNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\ShippingNodeBuilder(($this->services['PsCheckout\\Infrastructure\\Repository\\CountryRepository'] ?? $this->getCountryRepository2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] = new \PsCheckout\Infrastructure\Repository\StateRepository())));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\Node\SupplementaryDataNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\Node\SupplementaryDataNodeBuilder
     */
    protected function getSupplementaryDataNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\Node\\SupplementaryDataNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\SupplementaryDataNodeBuilder(($this->services['PsCheckout\\Infrastructure\\Repository\\CountryRepository'] ?? $this->getCountryRepository2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] = new \PsCheckout\Infrastructure\Repository\StateRepository())));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Builder\OrderPayloadBuilder' shared service.
     *
     * @return \PsCheckout\Core\Order\Builder\OrderPayloadBuilder
     */
    protected function getOrderPayloadBuilderService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Builder\\OrderPayloadBuilder'] = new \PsCheckout\Core\Order\Builder\OrderPayloadBuilder(($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\BaseNodeBuilder'] ?? $this->getBaseNodeBuilderService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\AmountBreakdownNode'] ?? ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\AmountBreakdownNode'] = new \PsCheckout\Core\Order\Builder\Node\AmountBreakdownNode())), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\ShippingNodeBuilder'] ?? $this->getShippingNodeBuilderService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PayerNodeBuilder'] ?? $this->getPayerNodeBuilderService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\CardPaymentSourceNodeBuilder'] ?? $this->getCardPaymentSourceNodeBuilderService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\SupplementaryDataNodeBuilder'] ?? $this->getSupplementaryDataNodeBuilderService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\ApplicationContextNodeBuilder'] ?? $this->getApplicationContextNodeBuilderService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PayPalPaymentSourceNodeBuilder'] ?? ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PayPalPaymentSourceNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\PayPalPaymentSourceNodeBuilder())), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\GooglePayPaymentSourceNodeBuilder'] ?? $this->getGooglePayPaymentSourceNodeBuilderService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PaymentSource\\VenmoPaymentSourceNodeBuilder'] ?? ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PaymentSource\\VenmoPaymentSourceNodeBuilder'] = new \PsCheckout\Core\Order\Builder\Node\PaymentSource\VenmoPaymentSourceNodeBuilder())), ($this->services['PsCheckout\\Core\\Order\\Builder\\Node\\PuiPaymentSourceNodeBuilder'] ?? $this->getPuiPaymentSourceNodeBuilderService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Exception\Handler\OrderCreationExceptionHandler' shared service.
     *
     * @return \PsCheckout\Core\Order\Exception\Handler\OrderCreationExceptionHandler
     */
    protected function getOrderCreationExceptionHandlerService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Exception\\Handler\\OrderCreationExceptionHandler'] = new \PsCheckout\Core\Order\Exception\Handler\OrderCreationExceptionHandler(($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Infrastructure\\Action\\CustomerNotifyAction'] ?? $this->getCustomerNotifyActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Processor\CreateOrderProcessor' shared service.
     *
     * @return \PsCheckout\Core\Order\Processor\CreateOrderProcessor
     */
    protected function getCreateOrderProcessorService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Processor\\CreateOrderProcessor'] = new \PsCheckout\Core\Order\Processor\CreateOrderProcessor(($this->services['PsCheckout\\Core\\Order\\Validator\\OrderAuthorizationValidator'] ?? $this->getOrderAuthorizationValidatorService()), ($this->services['PsCheckout\\Core\\Order\\Action\\CreateOrderAction'] ?? $this->getCreateOrderActionService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\CartRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\CartRepository'] = new \PsCheckout\Infrastructure\Repository\CartRepository())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Core\\Order\\Validator\\CheckoutValidator'] ?? $this->getCheckoutValidatorService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\CapturePayPalOrderAction'] ?? $this->getCapturePayPalOrderActionService()), ($this->services['PsCheckout\\Core\\PaymentToken\\Action\\SavePaymentTokenAction'] ?? $this->getSavePaymentTokenActionService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Core\\PaymentToken\\Action\\DeletePaymentTokenAction'] ?? $this->getDeletePaymentTokenActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Validator\CheckoutValidator' shared service.
     *
     * @return \PsCheckout\Core\Order\Validator\CheckoutValidator
     */
    protected function getCheckoutValidatorService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Validator\\CheckoutValidator'] = new \PsCheckout\Core\Order\Validator\CheckoutValidator(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Repository\\CartRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\CartRepository'] = new \PsCheckout\Infrastructure\Repository\CartRepository())));
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Validator\OrderAmountValidator' shared service.
     *
     * @return \PsCheckout\Core\Order\Validator\OrderAmountValidator
     */
    protected function getOrderAmountValidatorService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Validator\\OrderAmountValidator'] = new \PsCheckout\Core\Order\Validator\OrderAmountValidator();
    }

    /**
     * Gets the public 'PsCheckout\Core\Order\Validator\OrderAuthorizationValidator' shared service.
     *
     * @return \PsCheckout\Core\Order\Validator\OrderAuthorizationValidator
     */
    protected function getOrderAuthorizationValidatorService()
    {
        return $this->services['PsCheckout\\Core\\Order\\Validator\\OrderAuthorizationValidator'] = new \PsCheckout\Core\Order\Validator\OrderAuthorizationValidator(($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Customer'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Customer'] = new \PsCheckout\Infrastructure\Adapter\Customer())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Cart'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Cart'] = new \PsCheckout\Infrastructure\Adapter\Cart())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator'] ?? ($this->services['PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator'] = new \PsCheckout\Core\PayPal\Card3DSecure\Card3DSecureValidator())));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ApplePay\Builder\ApplePayPaymentRequestDataBuilder' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ApplePay\Builder\ApplePayPaymentRequestDataBuilder
     */
    protected function getApplePayPaymentRequestDataBuilderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ApplePay\\Builder\\ApplePayPaymentRequestDataBuilder'] = new \PsCheckout\Core\PayPal\ApplePay\Builder\ApplePayPaymentRequestDataBuilder(($this->services['PsCheckout\\Core\\Order\\Builder\\OrderPayloadBuilder'] ?? $this->getOrderPayloadBuilderService()), ($this->services['PsCheckout\\Presentation\\Presenter\\Cart\\CartPresenter'] ?? $this->getCartPresenterService()), ($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Card3DSecure\Card3DSecureValidator' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Card3DSecure\Card3DSecureValidator
     */
    protected function getCard3DSecureValidatorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator'] = new \PsCheckout\Core\PayPal\Card3DSecure\Card3DSecureValidator();
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\GooglePay\Builder\GooglePayPaymentRequestDataBuilder' shared service.
     *
     * @return \PsCheckout\Core\PayPal\GooglePay\Builder\GooglePayPaymentRequestDataBuilder
     */
    protected function getGooglePayPaymentRequestDataBuilderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\GooglePay\\Builder\\GooglePayPaymentRequestDataBuilder'] = new \PsCheckout\Core\PayPal\GooglePay\Builder\GooglePayPaymentRequestDataBuilder(($this->services['PsCheckout\\Core\\Order\\Builder\\OrderPayloadBuilder'] ?? $this->getOrderPayloadBuilderService()), ($this->services['PsCheckout\\Presentation\\Presenter\\Cart\\CartPresenter'] ?? $this->getCartPresenterService()), ($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\OAuth\OAuthService' shared service.
     *
     * @return \PsCheckout\Core\PayPal\OAuth\OAuthService
     */
    protected function getOAuthServiceService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\OAuth\\OAuthService'] = new \PsCheckout\Core\PayPal\OAuth\OAuthService(($this->services['PsCheckout\\Api\\Http\\CheckoutHttpClient'] ?? $this->getCheckoutHttpClientService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\OrderStatus\Action\PayPalCheckOrderStatusAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\OrderStatus\Action\PayPalCheckOrderStatusAction
     */
    protected function getPayPalCheckOrderStatusActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] = new \PsCheckout\Core\PayPal\OrderStatus\Action\PayPalCheckOrderStatusAction();
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Action\CancelPayPalOrderAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Action\CancelPayPalOrderAction
     */
    protected function getCancelPayPalOrderActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\CancelPayPalOrderAction'] = new \PsCheckout\Core\PayPal\Order\Action\CancelPayPalOrderAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Action\CapturePayPalOrderAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Action\CapturePayPalOrderAction
     */
    protected function getCapturePayPalOrderActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\CapturePayPalOrderAction'] = new \PsCheckout\Core\PayPal\Order\Action\CapturePayPalOrderAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Api\\Http\\OrderHttpClient'] ?? $this->getOrderHttpClientService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache'] ?? $this->getPayPalOrderCacheService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderCompletedEventHandler'] ?? $this->getOrderCompletedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentPendingEventHandler'] ?? $this->getPaymentPendingEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentCompletedEventHandler'] ?? $this->getPaymentCompletedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentDeniedEventHandler'] ?? $this->getPaymentDeniedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Action\CreatePayPalOrderAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Action\CreatePayPalOrderAction
     */
    protected function getCreatePayPalOrderActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\CreatePayPalOrderAction'] = new \PsCheckout\Core\PayPal\Order\Action\CreatePayPalOrderAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalCustomerRepository'] ?? $this->getPayPalCustomerRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Api\\Http\\OrderHttpClient'] ?? $this->getOrderHttpClientService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\OrderPayloadBuilder'] ?? $this->getOrderPayloadBuilderService()), ($this->services['PsCheckout\\Presentation\\Presenter\\Cart\\CartPresenter'] ?? $this->getCartPresenterService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Processor\\CreatePayPalOrderProcessor'] ?? $this->getCreatePayPalOrderProcessorService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache'] ?? $this->getPayPalOrderCacheService()), ($this->services['PsCheckout\\Core\\PaymentToken\\Action\\DeletePaymentTokenAction'] ?? $this->getDeletePaymentTokenActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Action\RefundPayPalOrderAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Action\RefundPayPalOrderAction
     */
    protected function getRefundPayPalOrderActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\RefundPayPalOrderAction'] = new \PsCheckout\Core\PayPal\Order\Action\RefundPayPalOrderAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Api\\Http\\OrderHttpClient'] ?? $this->getOrderHttpClientService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\SetRefundedOrderStateAction'] ?? $this->getSetRefundedOrderStateActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Action\UpdatePayPalOrderPurchaseUnitAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Action\UpdatePayPalOrderPurchaseUnitAction
     */
    protected function getUpdatePayPalOrderPurchaseUnitActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\UpdatePayPalOrderPurchaseUnitAction'] = new \PsCheckout\Core\PayPal\Order\Action\UpdatePayPalOrderPurchaseUnitAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderPurchaseUnitRepository'] ?? $this->getPayPalOrderPurchaseUnitRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderCaptureRepository'] ?? $this->getPayPalOrderCaptureRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderAuthorizationRepository'] ?? $this->getPayPalOrderAuthorizationRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRefundRepository'] ?? $this->getPayPalOrderRefundRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Cache\PayPalOrderCache' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Cache\PayPalOrderCache
     */
    protected function getPayPalOrderCacheService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache'] = new \PsCheckout\Core\PayPal\Order\Cache\PayPalOrderCache(($this->services['PsCheckout\\Cache\\Array\\PayPalOrder'] ?? ($this->services['PsCheckout\\Cache\\Array\\PayPalOrder'] = new \Symfony\Component\Cache\Adapter\ArrayAdapter())), ($this->services['PsCheckout\\Cache\\FileSystem\\PayPalOrder'] ?? $this->getPayPalOrder2Service()), ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] ?? ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] = new \PsCheckout\Core\PayPal\OrderStatus\Action\PayPalCheckOrderStatusAction())));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\OrderApprovalReversedEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\OrderApprovalReversedEventHandler
     */
    protected function getOrderApprovalReversedEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderApprovalReversedEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\OrderApprovalReversedEventHandler(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] ?? ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] = new \PsCheckout\Core\PayPal\OrderStatus\Action\PayPalCheckOrderStatusAction())));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\OrderApprovedEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\OrderApprovedEventHandler
     */
    protected function getOrderApprovedEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderApprovedEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\OrderApprovedEventHandler(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] ?? ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] = new \PsCheckout\Core\PayPal\OrderStatus\Action\PayPalCheckOrderStatusAction())), ($this->services['PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator'] ?? ($this->services['PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator'] = new \PsCheckout\Core\PayPal\Card3DSecure\Card3DSecureValidator())), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\CapturePayPalOrderAction'] ?? $this->getCapturePayPalOrderActionService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\UpdatePayPalOrderPurchaseUnitAction'] ?? $this->getUpdatePayPalOrderPurchaseUnitActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\OrderCompletedEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\OrderCompletedEventHandler
     */
    protected function getOrderCompletedEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderCompletedEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\OrderCompletedEventHandler(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] ?? ($this->services['PsCheckout\\Core\\PayPal\\OrderStatus\\Action\\PayPalCheckOrderStatusAction'] = new \PsCheckout\Core\PayPal\OrderStatus\Action\PayPalCheckOrderStatusAction())), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\UpdatePayPalOrderPurchaseUnitAction'] ?? $this->getUpdatePayPalOrderPurchaseUnitActionService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator'] ?? ($this->services['PsCheckout\\Core\\PayPal\\Card3DSecure\\Card3DSecureValidator'] = new \PsCheckout\Core\PayPal\Card3DSecure\Card3DSecureValidator())));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\PayPalEventDispatcher' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\PayPalEventDispatcher
     */
    protected function getPayPalEventDispatcherService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PayPalEventDispatcher'] = new \PsCheckout\Core\PayPal\Order\Handler\PayPalEventDispatcher(($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentCompletedEventHandler'] ?? $this->getPaymentCompletedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentPendingEventHandler'] ?? $this->getPaymentPendingEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentDeniedEventHandler'] ?? $this->getPaymentDeniedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentRefundedEventHandler'] ?? $this->getPaymentRefundedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentReversedEventHandler'] ?? $this->getPaymentReversedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderApprovedEventHandler'] ?? $this->getOrderApprovedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderCompletedEventHandler'] ?? $this->getOrderCompletedEventHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\OrderApprovalReversedEventHandler'] ?? $this->getOrderApprovalReversedEventHandlerService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\PaymentCompletedEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\PaymentCompletedEventHandler
     */
    protected function getPaymentCompletedEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentCompletedEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\PaymentCompletedEventHandler(($this->services['PsCheckout\\Core\\Order\\Action\\CreateOrderAction'] ?? $this->getCreateOrderActionService()), ($this->services['PsCheckout\\Core\\Order\\Action\\CreateOrderPaymentAction'] ?? $this->getCreateOrderPaymentActionService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\SetCompletedOrderStateAction'] ?? $this->getSetCompletedOrderStateActionService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\PaymentDeniedEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\PaymentDeniedEventHandler
     */
    protected function getPaymentDeniedEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentDeniedEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\PaymentDeniedEventHandler(($this->services['PsCheckout\\Core\\OrderState\\Action\\SetDeclinedOrderStateAction'] ?? $this->getSetDeclinedOrderStateActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\PaymentPendingEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\PaymentPendingEventHandler
     */
    protected function getPaymentPendingEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentPendingEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\PaymentPendingEventHandler(($this->services['PsCheckout\\Core\\Order\\Action\\CreateOrderAction'] ?? $this->getCreateOrderActionService()), ($this->services['PsCheckout\\Core\\OrderState\\Action\\SetPendingOrderStateAction'] ?? $this->getSetPendingOrderStateActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\PaymentRefundedEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\PaymentRefundedEventHandler
     */
    protected function getPaymentRefundedEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentRefundedEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\PaymentRefundedEventHandler(($this->services['PsCheckout\\Core\\OrderState\\Action\\SetRefundedOrderStateAction'] ?? $this->getSetRefundedOrderStateActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Handler\PaymentReversedEventHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Handler\PaymentReversedEventHandler
     */
    protected function getPaymentReversedEventHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PaymentReversedEventHandler'] = new \PsCheckout\Core\PayPal\Order\Handler\PaymentReversedEventHandler(($this->services['PsCheckout\\Core\\OrderState\\Action\\SetReversedOrderStateAction'] ?? $this->getSetReversedOrderStateActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Processor\CreatePayPalOrderProcessor' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Processor\CreatePayPalOrderProcessor
     */
    protected function getCreatePayPalOrderProcessorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Processor\\CreatePayPalOrderProcessor'] = new \PsCheckout\Core\PayPal\Order\Processor\CreatePayPalOrderProcessor(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalCustomerRepository'] ?? $this->getPayPalCustomerRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PaymentTokenRepository'] ?? $this->getPaymentTokenRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderPurchaseUnitRepository'] ?? $this->getPayPalOrderPurchaseUnitRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderCaptureRepository'] ?? $this->getPayPalOrderCaptureRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderAuthorizationRepository'] ?? $this->getPayPalOrderAuthorizationRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRefundRepository'] ?? $this->getPayPalOrderRefundRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Processor\UpdateExternalPayPalOrderProcessor' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Processor\UpdateExternalPayPalOrderProcessor
     */
    protected function getUpdateExternalPayPalOrderProcessorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Processor\\UpdateExternalPayPalOrderProcessor'] = new \PsCheckout\Core\PayPal\Order\Processor\UpdateExternalPayPalOrderProcessor(($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()), ($this->services['PsCheckout\\Presentation\\Presenter\\Cart\\CartPresenter'] ?? $this->getCartPresenterService()), ($this->services['PsCheckout\\Core\\Order\\Builder\\OrderPayloadBuilder'] ?? $this->getOrderPayloadBuilderService()), ($this->services['PsCheckout\\Api\\Http\\OrderHttpClient'] ?? $this->getOrderHttpClientService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache'] ?? $this->getPayPalOrderCacheService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Action\\UpdatePayPalOrderPurchaseUnitAction'] ?? $this->getUpdatePayPalOrderPurchaseUnitActionService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Provider\PayPalOrderProvider' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Provider\PayPalOrderProvider
     */
    protected function getPayPalOrderProviderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] = new \PsCheckout\Core\PayPal\Order\Provider\PayPalOrderProvider(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache'] ?? $this->getPayPalOrderCacheService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Api\\Http\\OrderHttpClient'] ?? $this->getOrderHttpClientService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Provider\PayPalOrderTranslationProvider' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Provider\PayPalOrderTranslationProvider
     */
    protected function getPayPalOrderTranslationProviderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderTranslationProvider'] = new \PsCheckout\Core\PayPal\Order\Provider\PayPalOrderTranslationProvider(($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Validator\CreatedPayPalOrderValidator' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Validator\CreatedPayPalOrderValidator
     */
    protected function getCreatedPayPalOrderValidatorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Validator\\CreatedPayPalOrderValidator'] = new \PsCheckout\Core\PayPal\Order\Validator\CreatedPayPalOrderValidator(($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Cart'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Cart'] = new \PsCheckout\Infrastructure\Adapter\Cart())), ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->id);
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Order\Validator\CreatedPayUponInvoiceOrderValidator' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Order\Validator\CreatedPayUponInvoiceOrderValidator
     */
    protected function getCreatedPayUponInvoiceOrderValidatorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Order\\Validator\\CreatedPayUponInvoiceOrderValidator'] = new \PsCheckout\Core\PayPal\Order\Validator\CreatedPayUponInvoiceOrderValidator(($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Cart'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Cart'] = new \PsCheckout\Infrastructure\Adapter\Cart())), ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->id);
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\Refund\Provider\PayPalRefundOrderProvider' shared service.
     *
     * @return \PsCheckout\Core\PayPal\Refund\Provider\PayPalRefundOrderProvider
     */
    protected function getPayPalRefundOrderProviderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\Refund\\Provider\\PayPalRefundOrderProvider'] = new \PsCheckout\Core\PayPal\Refund\Provider\PayPalRefundOrderProvider(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Action\AddTrackingAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Action\AddTrackingAction
     */
    protected function getAddTrackingActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Action\\AddTrackingAction'] = new \PsCheckout\Core\PayPal\ShippingTracking\Action\AddTrackingAction(($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ShipmentProcessor'] ?? $this->getShipmentProcessorService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Action\AddTrackingActionInterface' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Action\AddTrackingAction
     */
    protected function getAddTrackingActionInterfaceService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Action\\AddTrackingActionInterface'] = new \PsCheckout\Core\PayPal\ShippingTracking\Action\AddTrackingAction(($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ShipmentProcessorInterface'] ?? $this->getShipmentProcessorInterfaceService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Action\ProcessExternalShipmentAction' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Action\ProcessExternalShipmentAction
     */
    protected function getProcessExternalShipmentActionService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Action\\ProcessExternalShipmentAction'] = new \PsCheckout\Core\PayPal\ShippingTracking\Action\ProcessExternalShipmentAction(($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ExternalShipmentProcessor'] ?? $this->getExternalShipmentProcessorService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingBaseNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingBaseNodeBuilder
     */
    protected function getTrackingBaseNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingBaseNodeBuilder'] = new \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingBaseNodeBuilder();
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingCarrierModuleNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingCarrierModuleNodeBuilder
     */
    protected function getTrackingCarrierModuleNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingCarrierModuleNodeBuilder'] = new \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingCarrierModuleNodeBuilder();
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingItemsNodeBuilder' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingItemsNodeBuilder
     */
    protected function getTrackingItemsNodeBuilderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingItemsNodeBuilder'] = new \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingItemsNodeBuilder(($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Builder\TrackingPayloadBuilder' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Builder\TrackingPayloadBuilder
     */
    protected function getTrackingPayloadBuilderService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\TrackingPayloadBuilder'] = new \PsCheckout\Core\PayPal\ShippingTracking\Builder\TrackingPayloadBuilder(($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingBaseNodeBuilder'] ?? ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingBaseNodeBuilder'] = new \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingBaseNodeBuilder())), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingItemsNodeBuilder'] ?? $this->getTrackingItemsNodeBuilderService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingCarrierModuleNodeBuilder'] ?? ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\Node\\TrackingCarrierModuleNodeBuilder'] = new \PsCheckout\Core\PayPal\ShippingTracking\Builder\Node\TrackingCarrierModuleNodeBuilder())));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Cache\ShippingTrackingCache' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Cache\ShippingTrackingCache
     */
    protected function getShippingTrackingCacheService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Cache\\ShippingTrackingCache'] = new \PsCheckout\Core\PayPal\ShippingTracking\Cache\ShippingTrackingCache(($this->services['PsCheckout\\Cache\\Array\\ShippingTracking'] ?? ($this->services['PsCheckout\\Cache\\Array\\ShippingTracking'] = new \Symfony\Component\Cache\Adapter\ArrayAdapter())), ($this->services['PsCheckout\\Cache\\FileSystem\\ShippingTracking'] ?? $this->getShippingTracking2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Processor\ExternalShipmentProcessor' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Processor\ExternalShipmentProcessor
     */
    protected function getExternalShipmentProcessorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ExternalShipmentProcessor'] = new \PsCheckout\Core\PayPal\ShippingTracking\Processor\ExternalShipmentProcessor(($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Validator\\OrderTrackerValidator'] ?? $this->getOrderTrackerValidatorService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\TrackingPayloadBuilder'] ?? $this->getTrackingPayloadBuilderService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\ShippingTrackingRepository'] ?? $this->getShippingTrackingRepositoryService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Cache\\ShippingTrackingCache'] ?? $this->getShippingTrackingCacheService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingApiService'] ?? $this->getTrackingApiServiceService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingDatabaseHandler'] ?? $this->getTrackingDatabaseHandlerService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Action\\AddTrackingActionInterface'] ?? $this->getAddTrackingActionInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Processor\ShipmentProcessor' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Processor\ShipmentProcessor
     */
    protected function getShipmentProcessorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ShipmentProcessor'] = new \PsCheckout\Core\PayPal\ShippingTracking\Processor\ShipmentProcessor(($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Validator\\OrderTrackerValidator'] ?? $this->getOrderTrackerValidatorService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\TrackingPayloadBuilder'] ?? $this->getTrackingPayloadBuilderService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\ShippingTrackingRepository'] ?? $this->getShippingTrackingRepositoryService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Cache\\ShippingTrackingCache'] ?? $this->getShippingTrackingCacheService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingApiService'] ?? $this->getTrackingApiServiceService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingDatabaseHandler'] ?? $this->getTrackingDatabaseHandlerService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Processor\ShipmentProcessorInterface' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Processor\ShipmentProcessor
     */
    protected function getShipmentProcessorInterfaceService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Processor\\ShipmentProcessorInterface'] = new \PsCheckout\Core\PayPal\ShippingTracking\Processor\ShipmentProcessor(($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Validator\\OrderTrackerValidator'] ?? $this->getOrderTrackerValidatorService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Builder\\TrackingPayloadBuilder'] ?? $this->getTrackingPayloadBuilderService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\ShippingTrackingRepository'] ?? $this->getShippingTrackingRepositoryService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Cache\\ShippingTrackingCache'] ?? $this->getShippingTrackingCacheService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingApiService'] ?? $this->getTrackingApiServiceService()), ($this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingDatabaseHandler'] ?? $this->getTrackingDatabaseHandlerService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Service\TrackingApiService' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Service\TrackingApiService
     */
    protected function getTrackingApiServiceService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingApiService'] = new \PsCheckout\Core\PayPal\ShippingTracking\Service\TrackingApiService(($this->services['PsCheckout\\Api\\Http\\OrderShipmentTrackingHttpClient'] ?? $this->getOrderShipmentTrackingHttpClientService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Service\TrackingDatabaseHandler' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Service\TrackingDatabaseHandler
     */
    protected function getTrackingDatabaseHandlerService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Service\\TrackingDatabaseHandler'] = new \PsCheckout\Core\PayPal\ShippingTracking\Service\TrackingDatabaseHandler(($this->services['PsCheckout\\Infrastructure\\Repository\\ShippingTrackingRepository'] ?? $this->getShippingTrackingRepositoryService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PayPal\ShippingTracking\Validator\OrderTrackerValidator' shared service.
     *
     * @return \PsCheckout\Core\PayPal\ShippingTracking\Validator\OrderTrackerValidator
     */
    protected function getOrderTrackerValidatorService()
    {
        return $this->services['PsCheckout\\Core\\PayPal\\ShippingTracking\\Validator\\OrderTrackerValidator'] = new \PsCheckout\Core\PayPal\ShippingTracking\Validator\OrderTrackerValidator(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderCaptureRepository'] ?? $this->getPayPalOrderCaptureRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PaymentToken\Action\DeletePaymentTokenAction' shared service.
     *
     * @return \PsCheckout\Core\PaymentToken\Action\DeletePaymentTokenAction
     */
    protected function getDeletePaymentTokenActionService()
    {
        return $this->services['PsCheckout\\Core\\PaymentToken\\Action\\DeletePaymentTokenAction'] = new \PsCheckout\Core\PaymentToken\Action\DeletePaymentTokenAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PaymentTokenRepository'] ?? $this->getPaymentTokenRepositoryService()), ($this->services['PsCheckout\\Api\\Http\\CheckoutHttpClient'] ?? $this->getCheckoutHttpClientService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\PaymentToken\Action\SavePaymentTokenAction' shared service.
     *
     * @return \PsCheckout\Core\PaymentToken\Action\SavePaymentTokenAction
     */
    protected function getSavePaymentTokenActionService()
    {
        return $this->services['PsCheckout\\Core\\PaymentToken\\Action\\SavePaymentTokenAction'] = new \PsCheckout\Core\PaymentToken\Action\SavePaymentTokenAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalCustomerRepository'] ?? $this->getPayPalCustomerRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PaymentTokenRepository'] ?? $this->getPaymentTokenRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Settings\Configuration\PayPalConfiguration' shared service.
     *
     * @return \PsCheckout\Core\Settings\Configuration\PayPalConfiguration
     */
    protected function getPayPalConfigurationService()
    {
        return $this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalConfiguration'] = new \PsCheckout\Core\Settings\Configuration\PayPalConfiguration(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Settings\Configuration\PayPalPayLaterConfiguration' shared service.
     *
     * @return \PsCheckout\Core\Settings\Configuration\PayPalPayLaterConfiguration
     */
    protected function getPayPalPayLaterConfigurationService()
    {
        return $this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalPayLaterConfiguration'] = new \PsCheckout\Core\Settings\Configuration\PayPalPayLaterConfiguration(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Settings\Configuration\PayPalSdkConfiguration' shared service.
     *
     * @return \PsCheckout\Core\Settings\Configuration\PayPalSdkConfiguration
     */
    protected function getPayPalSdkConfigurationService()
    {
        return $this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalSdkConfiguration'] = new \PsCheckout\Core\Settings\Configuration\PayPalSdkConfiguration(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalConfiguration'] ?? $this->getPayPalConfigurationService()), ($this->services['PsCheckout\\Infrastructure\\Environment\\Env'] ?? $this->getEnv3Service()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourcePresenter'] ?? $this->getFundingSourcePresenterService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalCustomerRepository'] ?? $this->getPayPalCustomerRepositoryService()), ($this->services['PsCheckout\\Core\\PayPal\\OAuth\\OAuthService'] ?? $this->getOAuthServiceService()), ($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalPayLaterConfiguration'] ?? $this->getPayPalPayLaterConfigurationService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\WebhookDispatcher\Action\CheckPSLSignatureAction' shared service.
     *
     * @return \PsCheckout\Core\WebhookDispatcher\Action\CheckPSLSignatureAction
     */
    protected function getCheckPSLSignatureActionService()
    {
        return $this->services['PsCheckout\\Core\\WebhookDispatcher\\Action\\CheckPSLSignatureAction'] = new \PsCheckout\Core\WebhookDispatcher\Action\CheckPSLSignatureAction(($this->services['PsCheckout\\Api\\Http\\OrderHttpClient'] ?? $this->getOrderHttpClientService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\WebhookDispatcher\Processor\DispatchWebhookProcessor' shared service.
     *
     * @return \PsCheckout\Core\WebhookDispatcher\Processor\DispatchWebhookProcessor
     */
    protected function getDispatchWebhookProcessorService()
    {
        return $this->services['PsCheckout\\Core\\WebhookDispatcher\\Processor\\DispatchWebhookProcessor'] = new \PsCheckout\Core\WebhookDispatcher\Processor\DispatchWebhookProcessor(($this->services['Psr\\Log\\LoggerInterface'] ?? $this->getLoggerInterfaceService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Handler\\PayPalEventDispatcher'] ?? $this->getPayPalEventDispatcherService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Cache\\PayPalOrderCache'] ?? $this->getPayPalOrderCacheService()), ($this->services['PsCheckout\\Core\\PaymentToken\\Action\\SavePaymentTokenAction'] ?? $this->getSavePaymentTokenActionService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PaymentTokenRepository'] ?? $this->getPaymentTokenRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\WebhookDispatcher\Provider\WebhookBodyProvider' shared service.
     *
     * @return \PsCheckout\Core\WebhookDispatcher\Provider\WebhookBodyProvider
     */
    protected function getWebhookBodyProviderService()
    {
        return $this->services['PsCheckout\\Core\\WebhookDispatcher\\Provider\\WebhookBodyProvider'] = new \PsCheckout\Core\WebhookDispatcher\Provider\WebhookBodyProvider(($this->services['PsCheckout\\Utility\\Common\\InputStreamUtility'] ?? ($this->services['PsCheckout\\Utility\\Common\\InputStreamUtility'] = new \PsCheckout\Utility\Common\InputStreamUtility())));
    }

    /**
     * Gets the public 'PsCheckout\Core\WebhookDispatcher\Provider\WebhookHeaderProvider' shared service.
     *
     * @return \PsCheckout\Core\WebhookDispatcher\Provider\WebhookHeaderProvider
     */
    protected function getWebhookHeaderProviderService()
    {
        return $this->services['PsCheckout\\Core\\WebhookDispatcher\\Provider\\WebhookHeaderProvider'] = new \PsCheckout\Core\WebhookDispatcher\Provider\WebhookHeaderProvider();
    }

    /**
     * Gets the public 'PsCheckout\Core\WebhookDispatcher\Validator\BodyValuesValidator' shared service.
     *
     * @return \PsCheckout\Core\WebhookDispatcher\Validator\BodyValuesValidator
     */
    protected function getBodyValuesValidatorService()
    {
        return $this->services['PsCheckout\\Core\\WebhookDispatcher\\Validator\\BodyValuesValidator'] = new \PsCheckout\Core\WebhookDispatcher\Validator\BodyValuesValidator(($this->services['PsCheckout\\Core\\WebhookDispatcher\\Provider\\WebhookBodyProvider'] ?? $this->getWebhookBodyProviderService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\WebhookDispatcher\Validator\HeaderValuesValidator' shared service.
     *
     * @return \PsCheckout\Core\WebhookDispatcher\Validator\HeaderValuesValidator
     */
    protected function getHeaderValuesValidatorService()
    {
        return $this->services['PsCheckout\\Core\\WebhookDispatcher\\Validator\\HeaderValuesValidator'] = new \PsCheckout\Core\WebhookDispatcher\Validator\HeaderValuesValidator(($this->services['PsCheckout\\Core\\WebhookDispatcher\\Provider\\WebhookHeaderProvider'] ?? ($this->services['PsCheckout\\Core\\WebhookDispatcher\\Provider\\WebhookHeaderProvider'] = new \PsCheckout\Core\WebhookDispatcher\Provider\WebhookHeaderProvider())));
    }

    /**
     * Gets the public 'PsCheckout\Core\WebhookDispatcher\Validator\WebhookShopIdValidator' shared service.
     *
     * @return \PsCheckout\Core\WebhookDispatcher\Validator\WebhookShopIdValidator
     */
    protected function getWebhookShopIdValidatorService()
    {
        return $this->services['PsCheckout\\Core\\WebhookDispatcher\\Validator\\WebhookShopIdValidator'] = new \PsCheckout\Core\WebhookDispatcher\Validator\WebhookShopIdValidator(($this->services['PsCheckout\\Infrastructure\\Repository\\PsAccountRepository'] ?? $this->getPsAccountRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Webhook\Handler\WebhookEventConfigurationUpdatedHandler' shared service.
     *
     * @return \PsCheckout\Core\Webhook\Handler\WebhookEventConfigurationUpdatedHandler
     */
    protected function getWebhookEventConfigurationUpdatedHandlerService()
    {
        return $this->services['PsCheckout\\Core\\Webhook\\Handler\\WebhookEventConfigurationUpdatedHandler'] = new \PsCheckout\Core\Webhook\Handler\WebhookEventConfigurationUpdatedHandler(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Core\Webhook\Handler\WebhookHandler' shared service.
     *
     * @return \PsCheckout\Core\Webhook\Handler\WebhookHandler
     */
    protected function getWebhookHandlerService()
    {
        return $this->services['PsCheckout\\Core\\Webhook\\Handler\\WebhookHandler'] = new \PsCheckout\Core\Webhook\Handler\WebhookHandler(($this->services['PsCheckout\\Core\\Webhook\\Service\\WebhookSecretToken'] ?? $this->getWebhookSecretTokenService()), [0 => ($this->services['PsCheckout\\Core\\Webhook\\Handler\\WebhookEventConfigurationUpdatedHandler'] ?? $this->getWebhookEventConfigurationUpdatedHandlerService())]);
    }

    /**
     * Gets the public 'PsCheckout\Core\Webhook\Service\WebhookSecretToken' shared service.
     *
     * @return \PsCheckout\Core\Webhook\Service\WebhookSecretToken
     */
    protected function getWebhookSecretTokenService()
    {
        return $this->services['PsCheckout\\Core\\Webhook\\Service\\WebhookSecretToken'] = new \PsCheckout\Core\Webhook\Service\WebhookSecretToken(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Action\AddProductToCartAction' shared service.
     *
     * @return \PsCheckout\Infrastructure\Action\AddProductToCartAction
     */
    protected function getAddProductToCartActionService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Action\\AddProductToCartAction'] = new \PsCheckout\Infrastructure\Action\AddProductToCartAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Action\CreateOrUpdateAddressAction' shared service.
     *
     * @return \PsCheckout\Infrastructure\Action\CreateOrUpdateAddressAction
     */
    protected function getCreateOrUpdateAddressActionService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Action\\CreateOrUpdateAddressAction'] = new \PsCheckout\Infrastructure\Action\CreateOrUpdateAddressAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Country'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Country'] = new \PsCheckout\Infrastructure\Adapter\Country())), ($this->services['PsCheckout\\Infrastructure\\Repository\\CountryRepository'] ?? $this->getCountryRepository2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\AddressRepository'] ?? $this->getAddressRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Action\CustomerAuthenticationAction' shared service.
     *
     * @return \PsCheckout\Infrastructure\Action\CustomerAuthenticationAction
     */
    protected function getCustomerAuthenticationActionService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Action\\CustomerAuthenticationAction'] = new \PsCheckout\Infrastructure\Action\CustomerAuthenticationAction(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Customer'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Customer'] = new \PsCheckout\Infrastructure\Adapter\Customer())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Action\CustomerNotifyAction' shared service.
     *
     * @return \PsCheckout\Infrastructure\Action\CustomerNotifyAction
     */
    protected function getCustomerNotifyActionService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Action\\CustomerNotifyAction'] = new \PsCheckout\Infrastructure\Action\CustomerNotifyAction(($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository())), ($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()), ($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Address' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Address
     */
    protected function getAddressService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Address'] = new \PsCheckout\Infrastructure\Adapter\Address();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Cart' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Cart
     */
    protected function getCartService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Cart'] = new \PsCheckout\Infrastructure\Adapter\Cart();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Configuration' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Configuration
     */
    protected function getConfiguration2Service()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] = new \PsCheckout\Infrastructure\Adapter\Configuration(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Context' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Context
     */
    protected function getContextService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Country' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Country
     */
    protected function getCountryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Country'] = new \PsCheckout\Infrastructure\Adapter\Country();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Currency' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Currency
     */
    protected function getCurrencyService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Currency'] = new \PsCheckout\Infrastructure\Adapter\Currency();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Customer' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Customer
     */
    protected function getCustomerService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Customer'] = new \PsCheckout\Infrastructure\Adapter\Customer();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Language' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Language
     */
    protected function getLanguageService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Language'] = new \PsCheckout\Infrastructure\Adapter\Language();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Link' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Link
     */
    protected function getLinkService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Link'] = new \PsCheckout\Infrastructure\Adapter\Link(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name);
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\ShopContext' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\ShopContext
     */
    protected function getShopContextService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\ShopContext'] = new \PsCheckout\Infrastructure\Adapter\ShopContext();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\SystemConfiguration' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\SystemConfiguration
     */
    protected function getSystemConfigurationService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\SystemConfiguration'] = new \PsCheckout\Infrastructure\Adapter\SystemConfiguration();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Tools' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Tools
     */
    protected function getTools2Service()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Tools'] = new \PsCheckout\Infrastructure\Adapter\Tools();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Adapter\Validate' shared service.
     *
     * @return \PsCheckout\Infrastructure\Adapter\Validate
     */
    protected function getValidateService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Adapter\\Validate'] = new \PsCheckout\Infrastructure\Adapter\Validate();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Environment\Env' shared service.
     *
     * @return \PsCheckout\Infrastructure\Environment\Env
     */
    protected function getEnv3Service()
    {
        return $this->services['PsCheckout\\Infrastructure\\Environment\\Env'] = new \PsCheckout\Infrastructure\Environment\Env(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Environment\EnvLoader' shared service.
     *
     * @return \PsCheckout\Infrastructure\Environment\EnvLoader
     */
    protected function getEnvLoaderService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Environment\\EnvLoader'] = new \PsCheckout\Infrastructure\Environment\EnvLoader();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Logger\LoggerFactory' shared service.
     *
     * @return \PsCheckout\Infrastructure\Logger\LoggerFactory
     */
    protected function getLoggerFactoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Logger\\LoggerFactory'] = new \PsCheckout\Infrastructure\Logger\LoggerFactory(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['Monolog\\Handler\\HandlerInterface'] ?? $this->getHandlerInterfaceService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Logger\LoggerFileFinder' shared service.
     *
     * @return \PsCheckout\Infrastructure\Logger\LoggerFileFinder
     */
    protected function getLoggerFileFinderService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Logger\\LoggerFileFinder'] = new \PsCheckout\Infrastructure\Logger\LoggerFileFinder(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Logger\LoggerFileReader' shared service.
     *
     * @return \PsCheckout\Infrastructure\Logger\LoggerFileReader
     */
    protected function getLoggerFileReaderService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Logger\\LoggerFileReader'] = new \PsCheckout\Infrastructure\Logger\LoggerFileReader(($this->services['PsCheckout\\Infrastructure\\Adapter\\Validate'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Validate'] = new \PsCheckout\Infrastructure\Adapter\Validate())), ($this->services['PsCheckout\\Infrastructure\\Logger\\LoggerFileFinder'] ?? $this->getLoggerFileFinderService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Logger\LoggerHandlerFactory' shared service.
     *
     * @return \PsCheckout\Infrastructure\Logger\LoggerHandlerFactory
     */
    protected function getLoggerHandlerFactoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Logger\\LoggerHandlerFactory'] = new \PsCheckout\Infrastructure\Logger\LoggerHandlerFactory(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name);
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\AddressRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\AddressRepository
     */
    protected function getAddressRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\AddressRepository'] = new \PsCheckout\Infrastructure\Repository\AddressRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\CartRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\CartRepository
     */
    protected function getCartRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\CartRepository'] = new \PsCheckout\Infrastructure\Repository\CartRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\ConfigurationRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\ConfigurationRepository
     */
    protected function getConfigurationRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\ConfigurationRepository'] = new \PsCheckout\Infrastructure\Repository\ConfigurationRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\CountryRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\CountryRepository
     */
    protected function getCountryRepository2Service()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\CountryRepository'] = new \PsCheckout\Infrastructure\Repository\CountryRepository(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name);
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\CurrencyRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\CurrencyRepository
     */
    protected function getCurrencyRepository2Service()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\CurrencyRepository'] = new \PsCheckout\Infrastructure\Repository\CurrencyRepository(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name);
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\CustomerRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\CustomerRepository
     */
    protected function getCustomerRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\CustomerRepository'] = new \PsCheckout\Infrastructure\Repository\CustomerRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\FundingSourceRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\FundingSourceRepository
     */
    protected function getFundingSourceRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\FundingSourceRepository'] = new \PsCheckout\Infrastructure\Repository\FundingSourceRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\GenderRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\GenderRepository
     */
    protected function getGenderRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\GenderRepository'] = new \PsCheckout\Infrastructure\Repository\GenderRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\LanguageRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\LanguageRepository
     */
    protected function getLanguageRepository2Service()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\LanguageRepository'] = new \PsCheckout\Infrastructure\Repository\LanguageRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\OrderHistoryRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\OrderHistoryRepository
     */
    protected function getOrderHistoryRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\OrderHistoryRepository'] = new \PsCheckout\Infrastructure\Repository\OrderHistoryRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\OrderRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\OrderRepository
     */
    protected function getOrderRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\OrderRepository'] = new \PsCheckout\Infrastructure\Repository\OrderRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\OrderStateRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\OrderStateRepository
     */
    protected function getOrderStateRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\OrderStateRepository'] = new \PsCheckout\Infrastructure\Repository\OrderStateRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PayPalCustomerRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PayPalCustomerRepository
     */
    protected function getPayPalCustomerRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PayPalCustomerRepository'] = new \PsCheckout\Infrastructure\Repository\PayPalCustomerRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PayPalOrderAuthorizationRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PayPalOrderAuthorizationRepository
     */
    protected function getPayPalOrderAuthorizationRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderAuthorizationRepository'] = new \PsCheckout\Infrastructure\Repository\PayPalOrderAuthorizationRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PayPalOrderCaptureRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PayPalOrderCaptureRepository
     */
    protected function getPayPalOrderCaptureRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderCaptureRepository'] = new \PsCheckout\Infrastructure\Repository\PayPalOrderCaptureRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PayPalOrderMatrixRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PayPalOrderMatrixRepository
     */
    protected function getPayPalOrderMatrixRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderMatrixRepository'] = new \PsCheckout\Infrastructure\Repository\PayPalOrderMatrixRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PayPalOrderPurchaseUnitRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PayPalOrderPurchaseUnitRepository
     */
    protected function getPayPalOrderPurchaseUnitRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderPurchaseUnitRepository'] = new \PsCheckout\Infrastructure\Repository\PayPalOrderPurchaseUnitRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PayPalOrderRefundRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PayPalOrderRefundRepository
     */
    protected function getPayPalOrderRefundRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRefundRepository'] = new \PsCheckout\Infrastructure\Repository\PayPalOrderRefundRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PayPalOrderRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PayPalOrderRepository
     */
    protected function getPayPalOrderRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] = new \PsCheckout\Infrastructure\Repository\PayPalOrderRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PaymentTokenRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PaymentTokenRepository
     */
    protected function getPaymentTokenRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PaymentTokenRepository'] = new \PsCheckout\Infrastructure\Repository\PaymentTokenRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\PsAccountRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\PsAccountRepository
     */
    protected function getPsAccountRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\PsAccountRepository'] = new \PsCheckout\Infrastructure\Repository\PsAccountRepository(($this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Facade\\PsAccounts'] ?? $this->getPsAccountsService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\ShippingTrackingRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\ShippingTrackingRepository
     */
    protected function getShippingTrackingRepositoryService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\ShippingTrackingRepository'] = new \PsCheckout\Infrastructure\Repository\ShippingTrackingRepository(($this->services['ps_checkout.db'] ?? $this->getPsCheckout_DbService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Repository\StateRepository' shared service.
     *
     * @return \PsCheckout\Infrastructure\Repository\StateRepository
     */
    protected function getStateRepository2Service()
    {
        return $this->services['PsCheckout\\Infrastructure\\Repository\\StateRepository'] = new \PsCheckout\Infrastructure\Repository\StateRepository();
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Validator\FrontControllerValidator' shared service.
     *
     * @return \PsCheckout\Infrastructure\Validator\FrontControllerValidator
     */
    protected function getFrontControllerValidatorService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Validator\\FrontControllerValidator'] = new \PsCheckout\Infrastructure\Validator\FrontControllerValidator(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalPayLaterConfiguration'] ?? $this->getPayPalPayLaterConfigurationService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Validator\MerchantValidator' shared service.
     *
     * @return \PsCheckout\Infrastructure\Validator\MerchantValidator
     */
    protected function getMerchantValidatorService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Validator\\MerchantValidator'] = new \PsCheckout\Infrastructure\Validator\MerchantValidator(($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PsAccountRepository'] ?? $this->getPsAccountRepositoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Infrastructure\Validator\PayLaterValidator' shared service.
     *
     * @return \PsCheckout\Infrastructure\Validator\PayLaterValidator
     */
    protected function getPayLaterValidatorService()
    {
        return $this->services['PsCheckout\\Infrastructure\\Validator\\PayLaterValidator'] = new \PsCheckout\Infrastructure\Validator\PayLaterValidator(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalConfiguration'] ?? $this->getPayPalConfigurationService()));
    }

    /**
     * Gets the public 'PsCheckout\Module\Presentation\Translator' shared service.
     *
     * @return \PsCheckout\Module\Presentation\Translator
     */
    protected function getTranslatorService()
    {
        return $this->services['PsCheckout\\Module\\Presentation\\Translator'] = new \PsCheckout\Module\Presentation\Translator(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->getTranslator());
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Cart\CartPresenter' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Cart\CartPresenter
     */
    protected function getCartPresenterService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Cart\\CartPresenter'] = new \PsCheckout\Presentation\Presenter\Cart\CartPresenter(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Address'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Address'] = new \PsCheckout\Infrastructure\Adapter\Address())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Currency'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Currency'] = new \PsCheckout\Infrastructure\Adapter\Currency())), ($this->services['PsCheckout\\Infrastructure\\Repository\\LanguageRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\LanguageRepository'] = new \PsCheckout\Infrastructure\Repository\LanguageRepository())), ($this->services['PsCheckout\\Infrastructure\\Repository\\CustomerRepository'] ?? ($this->services['PsCheckout\\Infrastructure\\Repository\\CustomerRepository'] = new \PsCheckout\Infrastructure\Repository\CustomerRepository())));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\FundingSource\FundingSourcePresenter' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\FundingSource\FundingSourcePresenter
     */
    protected function getFundingSourcePresenterService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourcePresenter'] = new \PsCheckout\Presentation\Presenter\FundingSource\FundingSourcePresenter(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->getPathUri(), ($this->services['PsCheckout\\Infrastructure\\Repository\\FundingSourceRepository'] ?? $this->getFundingSourceRepositoryService()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider'] ?? $this->getFundingSourceTranslationProviderService()));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\FundingSource\FundingSourceTokenPresenter' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\FundingSource\FundingSourceTokenPresenter
     */
    protected function getFundingSourceTokenPresenterService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTokenPresenter'] = new \PsCheckout\Presentation\Presenter\FundingSource\FundingSourceTokenPresenter(($this->services['PsCheckout\\Infrastructure\\Repository\\PaymentTokenRepository'] ?? $this->getPaymentTokenRepositoryService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\FundingSource\\Factory\\FundingSourceTokenFactory'] ?? $this->getFundingSourceTokenFactoryService()));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\FundingSource\FundingSourceTranslationProvider' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\FundingSource\FundingSourceTranslationProvider
     */
    protected function getFundingSourceTranslationProviderService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider'] = new \PsCheckout\Presentation\Presenter\FundingSource\FundingSourceTranslationProvider(($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\FundingSource\LogoPresenter' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\FundingSource\LogoPresenter
     */
    protected function getLogoPresenterService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\LogoPresenter'] = new \PsCheckout\Presentation\Presenter\FundingSource\LogoPresenter(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->getPathUri());
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\OrderSummary\OrderSummaryPresenter' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\OrderSummary\OrderSummaryPresenter
     */
    protected function getOrderSummaryPresenterService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\OrderSummary\\OrderSummaryPresenter'] = new \PsCheckout\Presentation\Presenter\OrderSummary\OrderSummaryPresenter(($this->services['PsCheckout\\Infrastructure\\Adapter\\Link'] ?? $this->getLinkService()), ($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderProvider'] ?? $this->getPayPalOrderProviderService()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider'] ?? $this->getFundingSourceTranslationProviderService()), ($this->services['PsCheckout\\Core\\PayPal\\Order\\Provider\\PayPalOrderTranslationProvider'] ?? $this->getPayPalOrderTranslationProviderService()));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Settings\Front\FrontSettingsPresenter' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Settings\Front\FrontSettingsPresenter
     */
    protected function getFrontSettingsPresenterService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\FrontSettingsPresenter'] = new \PsCheckout\Presentation\Presenter\Settings\Front\FrontSettingsPresenter([0 => ($this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\PayPalModule'] ?? $this->getPayPalModuleService()), 1 => ($this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\ConfigurationModule'] ?? $this->getConfigurationModuleService()), 2 => ($this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\MediaModule'] ?? $this->getMediaModuleService()), 3 => ($this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\LinkModule'] ?? $this->getLinkModuleService()), 4 => ($this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\TranslationModule'] ?? $this->getTranslationModuleService())]);
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Settings\Front\Modules\ConfigurationModule' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Settings\Front\Modules\ConfigurationModule
     */
    protected function getConfigurationModuleService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\ConfigurationModule'] = new \PsCheckout\Presentation\Presenter\Settings\Front\Modules\ConfigurationModule(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Configuration'] ?? $this->getConfiguration2Service()), ($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalConfiguration'] ?? $this->getPayPalConfigurationService()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourcePresenter'] ?? $this->getFundingSourcePresenterService()), ($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalSdkConfiguration'] ?? $this->getPayPalSdkConfigurationService()), ($this->services['PsCheckout\\Core\\Settings\\Configuration\\PayPalPayLaterConfiguration'] ?? $this->getPayPalPayLaterConfigurationService()), ($this->services['PsCheckout\\Infrastructure\\Validator\\PayLaterValidator'] ?? $this->getPayLaterValidatorService()));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Settings\Front\Modules\LinkModule' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Settings\Front\Modules\LinkModule
     */
    protected function getLinkModuleService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\LinkModule'] = new \PsCheckout\Presentation\Presenter\Settings\Front\Modules\LinkModule(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['PsCheckout\\Infrastructure\\Adapter\\Link'] ?? $this->getLinkService()), ($this->services['PsCheckout\\Infrastructure\\Adapter\\Tools'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Tools'] = new \PsCheckout\Infrastructure\Adapter\Tools())));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Settings\Front\Modules\MediaModule' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Settings\Front\Modules\MediaModule
     */
    protected function getMediaModuleService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\MediaModule'] = new \PsCheckout\Presentation\Presenter\Settings\Front\Modules\MediaModule(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->getPathUri());
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Settings\Front\Modules\PayPalModule' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Settings\Front\Modules\PayPalModule
     */
    protected function getPayPalModuleService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\PayPalModule'] = new \PsCheckout\Presentation\Presenter\Settings\Front\Modules\PayPalModule(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->version, ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())), ($this->services['PsCheckout\\Infrastructure\\Environment\\Env'] ?? $this->getEnv3Service()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourcePresenter'] ?? $this->getFundingSourcePresenterService()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTokenPresenter'] ?? $this->getFundingSourceTokenPresenterService()), ($this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\SupportedCardBrandsPresenter'] ?? $this->getSupportedCardBrandsPresenterService()), ($this->services['PsCheckout\\Infrastructure\\Repository\\PayPalOrderRepository'] ?? $this->getPayPalOrderRepositoryService()), ($this->services['PsCheckout\\Presentation\\Presenter\\FundingSource\\FundingSourceTranslationProvider'] ?? $this->getFundingSourceTranslationProviderService()));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Settings\Front\Modules\TranslationModule' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Settings\Front\Modules\TranslationModule
     */
    protected function getTranslationModuleService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\Modules\\TranslationModule'] = new \PsCheckout\Presentation\Presenter\Settings\Front\Modules\TranslationModule(($this->services['ps_checkout.module'] ?? $this->getPsCheckout_ModuleService())->name, ($this->services['PsCheckout\\Module\\Presentation\\Translator'] ?? $this->getTranslatorService()));
    }

    /**
     * Gets the public 'PsCheckout\Presentation\Presenter\Settings\Front\SupportedCardBrandsPresenter' shared service.
     *
     * @return \PsCheckout\Presentation\Presenter\Settings\Front\SupportedCardBrandsPresenter
     */
    protected function getSupportedCardBrandsPresenterService()
    {
        return $this->services['PsCheckout\\Presentation\\Presenter\\Settings\\Front\\SupportedCardBrandsPresenter'] = new \PsCheckout\Presentation\Presenter\Settings\Front\SupportedCardBrandsPresenter(($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] ?? ($this->services['PsCheckout\\Infrastructure\\Adapter\\Context'] = new \PsCheckout\Infrastructure\Adapter\Context())));
    }

    /**
     * Gets the public 'PsCheckout\Utility\Common\InputStreamUtility' shared service.
     *
     * @return \PsCheckout\Utility\Common\InputStreamUtility
     */
    protected function getInputStreamUtilityService()
    {
        return $this->services['PsCheckout\\Utility\\Common\\InputStreamUtility'] = new \PsCheckout\Utility\Common\InputStreamUtility();
    }

    /**
     * Gets the public 'Psr\Log\LoggerInterface' shared service.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLoggerInterfaceService()
    {
        return $this->services['Psr\\Log\\LoggerInterface'] = ($this->services['PsCheckout\\Infrastructure\\Logger\\LoggerFactory'] ?? $this->getLoggerFactoryService())->build();
    }

    /**
     * Gets the public 'annotation_reader' shared service.
     *
     * @return \Doctrine\Common\Annotations\AnnotationReader
     */
    protected function getAnnotationReaderService()
    {
        return $this->services['annotation_reader'] = new \Doctrine\Common\Annotations\AnnotationReader();
    }

    /**
     * Gets the public 'array' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\AdapterInterface
     */
    protected function getArrayService()
    {
        return $this->services['array'] = (new \PrestaShopBundle\DependencyInjection\CacheAdapterFactory())->getCacheAdapter('array');
    }

    /**
     * Gets the public 'configuration' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Configuration
     */
    protected function getConfiguration3Service()
    {
        return $this->services['configuration'] = new \PrestaShop\PrestaShop\Adapter\Configuration();
    }

    /**
     * Gets the public 'container.env_var_processors_locator' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    protected function getContainer_EnvVarProcessorsLocatorService()
    {
        return $this->services['container.env_var_processors_locator'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
            'const' => ['privates', 'PrestaShopBundle\\DependencyInjection\\RuntimeConstEnvVarProcessor', 'getRuntimeConstEnvVarProcessorService', false],
        ], [
            'const' => '?',
        ]);
    }

    /**
     * Gets the public 'context' shared service.
     *
     * @return \Context
     */
    protected function getContext2Service()
    {
        return $this->services['context'] = \Context::getContext();
    }

    /**
     * Gets the public 'db' shared service.
     *
     * @return \Db
     */
    protected function getDbService()
    {
        return $this->services['db'] = \Db::getInstance();
    }

    /**
     * Gets the public 'doctrine' shared service.
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrineService()
    {
        return $this->services['doctrine'] = new \Doctrine\Bundle\DoctrineBundle\Registry($this, $this->parameters['doctrine.connections'], $this->parameters['doctrine.entity_managers'], 'default', 'default');
    }

    /**
     * Gets the public 'doctrine.dbal.default_connection' shared service.
     *
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDoctrine_Dbal_DefaultConnectionService()
    {
        return $this->services['doctrine.dbal.default_connection'] = (new \Doctrine\Bundle\DoctrineBundle\ConnectionFactory([]))->createConnection(['driver' => 'pdo_mysql', 'host' => 'db', 'port' => '', 'dbname' => 'prestashop', 'user' => 'ps_user', 'password' => 'ps_pass', 'charset' => 'utf8mb4', 'driverOptions' => [1002 => 'SET sql_mode=(SELECT REPLACE(@@sql_mode,\'ONLY_FULL_GROUP_BY\',\'\'))', 1013 => $this->getEnv('const:runtime:_PS_ALLOW_MULTI_STATEMENTS_QUERIES_')], 'defaultTableOptions' => []], new \Doctrine\DBAL\Configuration(), new \Symfony\Bridge\Doctrine\ContainerAwareEventManager($this), ['enum' => 'string']);
    }

    /**
     * Gets the public 'doctrine.orm.default_entity_manager' shared service.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getDoctrine_Orm_DefaultEntityManagerService($lazyLoad = true)
    {
        $a = new \Doctrine\ORM\Configuration();

        $b = ($this->services['array'] ?? $this->getArrayService());
        $c = new \Doctrine\Persistence\Mapping\Driver\MappingDriverChain();

        $d = ($this->services['annotation_reader'] ?? ($this->services['annotation_reader'] = new \Doctrine\Common\Annotations\AnnotationReader()));
        $e = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($d, [0 => '/var/www/html/modules/productcomments/src/Entity']);
        $e->addExcludePaths([0 => '/var/www/html/modules/productcomments/src/Entity/index.php']);
        $f = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($d, [0 => '/var/www/html/modules/ps_accounts/src/Entity']);
        $f->addExcludePaths([0 => '/var/www/html/modules/ps_accounts/src/Entity/index.php']);

        $c->addDriver(new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($d, [0 => '/var/www/html/src/PrestaShopBundle/Entity']), 'PrestaShop');
        $c->addDriver($e, 'PrestaShop\\Module\\ProductComment\\Entity');
        $c->addDriver($f, 'PrestaShop\\Module\\PsAccounts\\Entity');

        $a->setEntityNamespaces(['PrestaShopBundle\\Entity' => 'PrestaShop']);
        $a->setMetadataCache($b);
        $a->setQueryCache($b);
        $a->setResultCache($b);
        $a->setMetadataDriverImpl($c);
        $a->setProxyDir('/var/www/html/var/cache/prod//doctrine/orm/Proxies');
        $a->setProxyNamespace('Proxies');
        $a->setAutoGenerateProxyClasses(false);
        $a->setClassMetadataFactoryName('Doctrine\\ORM\\Mapping\\ClassMetadataFactory');
        $a->setDefaultRepositoryClassName('Doctrine\\ORM\\EntityRepository');
        $a->setNamingStrategy(($this->services['prestashop.database.naming_strategy'] ?? ($this->services['prestashop.database.naming_strategy'] = new \PrestaShopBundle\Service\Database\DoctrineNamingStrategy('ps_'))));
        $a->setQuoteStrategy(new \Doctrine\ORM\Mapping\DefaultQuoteStrategy());
        $a->setEntityListenerResolver(new \Doctrine\Bundle\DoctrineBundle\Mapping\ContainerEntityListenerResolver($this));
        $a->setRepositoryFactory(new \Doctrine\Bundle\DoctrineBundle\Repository\ContainerRepositoryFactory(new \Symfony\Component\DependencyInjection\ServiceLocator([])));
        $a->addCustomStringFunction('regexp', 'DoctrineExtensions\\Query\\Mysql\\Regexp');
        $a->addEntityNamespace('Moduleproductcomments', 'PrestaShop\\Module\\ProductComment\\Entity');
        $a->addEntityNamespace('ModulepsAccounts', 'PrestaShop\\Module\\PsAccounts\\Entity');

        $this->services['doctrine.orm.default_entity_manager'] = $instance = \Doctrine\ORM\EntityManager::create(($this->services['doctrine.dbal.default_connection'] ?? $this->getDoctrine_Dbal_DefaultConnectionService()), $a);

        (new \Doctrine\Bundle\DoctrineBundle\ManagerConfigurator([], []))->configure($instance);

        return $instance;
    }

    /**
     * Gets the public 'hashing' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Crypto\Hashing
     */
    protected function getHashingService()
    {
        return $this->services['hashing'] = new \PrestaShop\PrestaShop\Core\Crypto\Hashing();
    }

    /**
     * Gets the public 'klaviyops.klaviyo_api_wrapper' shared service.
     *
     * @return \KlaviyoPs\Classes\KlaviyoApiWrapper
     */
    protected function getKlaviyops_KlaviyoApiWrapperService()
    {
        return $this->services['klaviyops.klaviyo_api_wrapper'] = new \KlaviyoPs\Classes\KlaviyoApiWrapper();
    }

    /**
     * Gets the public 'klaviyops.klaviyo_service.coupon_generator' shared service.
     *
     * @return \KlaviyoPs\Classes\KlaviyoServices\CouponGeneratorService
     */
    protected function getKlaviyops_KlaviyoService_CouponGeneratorService()
    {
        return $this->services['klaviyops.klaviyo_service.coupon_generator'] = new \KlaviyoPs\Classes\KlaviyoServices\CouponGeneratorService(($this->services['klaviyops.prestashop_services.cart_rule'] ?? ($this->services['klaviyops.prestashop_services.cart_rule'] = new \KlaviyoPs\Classes\PrestashopServices\CartRuleService())));
    }

    /**
     * Gets the public 'klaviyops.klaviyo_service.customer_event_service' shared service.
     *
     * @return \KlaviyoPs\Classes\KlaviyoServices\CustomerEventService
     */
    protected function getKlaviyops_KlaviyoService_CustomerEventServiceService()
    {
        return $this->services['klaviyops.klaviyo_service.customer_event_service'] = new \KlaviyoPs\Classes\KlaviyoServices\CustomerEventService(($this->services['klaviyops.prestashop_services.datetime'] ?? ($this->services['klaviyops.prestashop_services.datetime'] = new \KlaviyoPs\Classes\PrestashopServices\DateTimeService())), ($this->services['klaviyops.prestashop_services.context'] ?? $this->getKlaviyops_PrestashopServices_ContextService()), ($this->services['klaviyops.prestashop_services.customer'] ?? $this->getKlaviyops_PrestashopServices_CustomerService()));
    }

    /**
     * Gets the public 'klaviyops.klaviyo_service.order_event' shared service.
     *
     * @return \KlaviyoPs\Classes\KlaviyoServices\OrderEventService
     */
    protected function getKlaviyops_KlaviyoService_OrderEventService()
    {
        return $this->services['klaviyops.klaviyo_service.order_event'] = new \KlaviyoPs\Classes\KlaviyoServices\OrderEventService(($this->services['klaviyops.klaviyo_api_wrapper'] ?? ($this->services['klaviyops.klaviyo_api_wrapper'] = new \KlaviyoPs\Classes\KlaviyoApiWrapper())), ($this->services['klaviyops.prestashop_services.order'] ?? $this->getKlaviyops_PrestashopServices_OrderService()), ($this->services['klaviyops.prestashop_services.product'] ?? ($this->services['klaviyops.prestashop_services.product'] = new \KlaviyoPs\Classes\PrestashopServices\ProductService())), ($this->services['klaviyops.klaviyo_service.customer_event_service'] ?? $this->getKlaviyops_KlaviyoService_CustomerEventServiceService()));
    }

    /**
     * Gets the public 'klaviyops.klaviyo_service.profile_event' shared service.
     *
     * @return \KlaviyoPs\Classes\KlaviyoServices\ProfileEventService
     */
    protected function getKlaviyops_KlaviyoService_ProfileEventService()
    {
        return $this->services['klaviyops.klaviyo_service.profile_event'] = new \KlaviyoPs\Classes\KlaviyoServices\ProfileEventService(($this->services['klaviyops.klaviyo_api_wrapper'] ?? ($this->services['klaviyops.klaviyo_api_wrapper'] = new \KlaviyoPs\Classes\KlaviyoApiWrapper())), ($this->services['klaviyops.klaviyo_service.customer_event_service'] ?? $this->getKlaviyops_KlaviyoService_CustomerEventServiceService()));
    }

    /**
     * Gets the public 'klaviyops.module' shared service.
     *
     * @return \KlaviyoPsModule
     */
    protected function getKlaviyops_ModuleService()
    {
        return $this->services['klaviyops.module'] = \KlaviyoPsModule::getInstance();
    }

    /**
     * Gets the public 'klaviyops.prestashop_components.context' shared service.
     *
     * @return \Context
     */
    protected function getKlaviyops_PrestashopComponents_ContextService()
    {
        return $this->services['klaviyops.prestashop_components.context'] = \Context::getContext();
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.cart_rule' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\CartRuleService
     */
    protected function getKlaviyops_PrestashopServices_CartRuleService()
    {
        return $this->services['klaviyops.prestashop_services.cart_rule'] = new \KlaviyoPs\Classes\PrestashopServices\CartRuleService();
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.context' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\ContextService
     */
    protected function getKlaviyops_PrestashopServices_ContextService()
    {
        return $this->services['klaviyops.prestashop_services.context'] = new \KlaviyoPs\Classes\PrestashopServices\ContextService(($this->services['klaviyops.prestashop_components.context'] ?? $this->getKlaviyops_PrestashopComponents_ContextService()));
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.customer' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\CustomerService
     */
    protected function getKlaviyops_PrestashopServices_CustomerService()
    {
        return $this->services['klaviyops.prestashop_services.customer'] = new \KlaviyoPs\Classes\PrestashopServices\CustomerService(($this->services['klaviyops.prestashop_services.validate'] ?? ($this->services['klaviyops.prestashop_services.validate'] = new \KlaviyoPs\Classes\PrestashopServices\ValidateService())), ($this->services['klaviyops.prestashop_services.datetime'] ?? ($this->services['klaviyops.prestashop_services.datetime'] = new \KlaviyoPs\Classes\PrestashopServices\DateTimeService())), ($this->services['klaviyops.prestashop_services.context'] ?? $this->getKlaviyops_PrestashopServices_ContextService()));
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.datetime' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\DateTimeService
     */
    protected function getKlaviyops_PrestashopServices_DatetimeService()
    {
        return $this->services['klaviyops.prestashop_services.datetime'] = new \KlaviyoPs\Classes\PrestashopServices\DateTimeService();
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.logger' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\LoggerService
     */
    protected function getKlaviyops_PrestashopServices_LoggerService()
    {
        return $this->services['klaviyops.prestashop_services.logger'] = new \KlaviyoPs\Classes\PrestashopServices\LoggerService();
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.order' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\OrderService
     */
    protected function getKlaviyops_PrestashopServices_OrderService()
    {
        return $this->services['klaviyops.prestashop_services.order'] = new \KlaviyoPs\Classes\PrestashopServices\OrderService(($this->services['klaviyops.prestashop_services.context'] ?? $this->getKlaviyops_PrestashopServices_ContextService()), ($this->services['klaviyops.prestashop_services.product'] ?? ($this->services['klaviyops.prestashop_services.product'] = new \KlaviyoPs\Classes\PrestashopServices\ProductService())), ($this->services['klaviyops.prestashop_services.customer'] ?? $this->getKlaviyops_PrestashopServices_CustomerService()));
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.product' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\ProductService
     */
    protected function getKlaviyops_PrestashopServices_ProductService()
    {
        return $this->services['klaviyops.prestashop_services.product'] = new \KlaviyoPs\Classes\PrestashopServices\ProductService();
    }

    /**
     * Gets the public 'klaviyops.prestashop_services.validate' shared service.
     *
     * @return \KlaviyoPs\Classes\PrestashopServices\ValidateService
     */
    protected function getKlaviyops_PrestashopServices_ValidateService()
    {
        return $this->services['klaviyops.prestashop_services.validate'] = new \KlaviyoPs\Classes\PrestashopServices\ValidateService();
    }

    /**
     * Gets the public 'klaviyops.util_services.csv' shared service.
     *
     * @return \KlaviyoPs\Classes\UtilServices\CsvService
     */
    protected function getKlaviyops_UtilServices_CsvService()
    {
        return $this->services['klaviyops.util_services.csv'] = new \KlaviyoPs\Classes\UtilServices\CsvService();
    }

    /**
     * Gets the public 'klaviyops.util_services.env' shared service.
     *
     * @return \KlaviyoPs\Classes\UtilServices\EnvService
     */
    protected function getKlaviyops_UtilServices_EnvService()
    {
        return $this->services['klaviyops.util_services.env'] = new \KlaviyoPs\Classes\UtilServices\EnvService(($this->services['klaviyops.module'] ?? $this->getKlaviyops_ModuleService()));
    }

    /**
     * Gets the public 'prestashop.adapter.data_provider.country' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Country\CountryDataProvider
     */
    protected function getPrestashop_Adapter_DataProvider_CountryService()
    {
        return $this->services['prestashop.adapter.data_provider.country'] = new \PrestaShop\PrestaShop\Adapter\Country\CountryDataProvider();
    }

    /**
     * Gets the public 'prestashop.adapter.environment' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Environment
     */
    protected function getPrestashop_Adapter_EnvironmentService()
    {
        return $this->services['prestashop.adapter.environment'] = new \PrestaShop\PrestaShop\Adapter\Environment(false);
    }

    /**
     * Gets the public 'prestashop.adapter.module.repository.module_repository' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Module\Repository\ModuleRepository
     */
    protected function getPrestashop_Adapter_Module_Repository_ModuleRepositoryService()
    {
        return $this->services['prestashop.adapter.module.repository.module_repository'] = new \PrestaShop\PrestaShop\Adapter\Module\Repository\ModuleRepository('/var/www/html', '/var/www/html/modules/');
    }

    /**
     * Gets the public 'prestashop.adapter.validate' shared service.
     *
     * @return \PrestaShop\PrestaShop\Adapter\Validate
     */
    protected function getPrestashop_Adapter_ValidateService()
    {
        return $this->services['prestashop.adapter.validate'] = new \PrestaShop\PrestaShop\Adapter\Validate();
    }

    /**
     * Gets the public 'prestashop.core.circuit_breaker.advanced_factory' shared service.
     *
     * @return \PrestaShop\CircuitBreaker\AdvancedCircuitBreakerFactory
     */
    protected function getPrestashop_Core_CircuitBreaker_AdvancedFactoryService()
    {
        return $this->services['prestashop.core.circuit_breaker.advanced_factory'] = new \PrestaShop\CircuitBreaker\AdvancedCircuitBreakerFactory();
    }

    /**
     * Gets the public 'prestashop.core.circuit_breaker.cache' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\FilesystemAdapter
     */
    protected function getPrestashop_Core_CircuitBreaker_CacheService()
    {
        return $this->services['prestashop.core.circuit_breaker.cache'] = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('', 0, (($this->services['prestashop.adapter.environment'] ?? ($this->services['prestashop.adapter.environment'] = new \PrestaShop\PrestaShop\Adapter\Environment(false)))->getCacheDir() . "/circuit_breaker"));
    }

    /**
     * Gets the public 'prestashop.core.circuit_breaker.doctrine_cache' shared service.
     *
     * @return \Symfony\Component\Cache\DoctrineProvider
     */
    protected function getPrestashop_Core_CircuitBreaker_DoctrineCacheService()
    {
        return $this->services['prestashop.core.circuit_breaker.doctrine_cache'] = new \Symfony\Component\Cache\DoctrineProvider(($this->services['prestashop.core.circuit_breaker.cache'] ?? $this->getPrestashop_Core_CircuitBreaker_CacheService()));
    }

    /**
     * Gets the public 'prestashop.core.circuit_breaker.storage' shared service.
     *
     * @return \PrestaShop\CircuitBreaker\Storage\DoctrineCache
     */
    protected function getPrestashop_Core_CircuitBreaker_StorageService()
    {
        return $this->services['prestashop.core.circuit_breaker.storage'] = new \PrestaShop\CircuitBreaker\Storage\DoctrineCache(($this->services['prestashop.core.circuit_breaker.doctrine_cache'] ?? $this->getPrestashop_Core_CircuitBreaker_DoctrineCacheService()));
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.cart' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\CartFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_CartService()
    {
        return $this->services['prestashop.core.filter.front_end_object.cart'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\CartFilter(($this->services['prestashop.core.filter.front_end_object.product_collection'] ?? $this->getPrestashop_Core_Filter_FrontEndObject_ProductCollectionService()));
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.configuration' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ConfigurationFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_ConfigurationService()
    {
        return $this->services['prestashop.core.filter.front_end_object.configuration'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ConfigurationFilter();
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.customer' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\CustomerFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_CustomerService()
    {
        return $this->services['prestashop.core.filter.front_end_object.customer'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\CustomerFilter();
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.main' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\MainFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_MainService()
    {
        return $this->services['prestashop.core.filter.front_end_object.main'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\MainFilter(['cart' => ($this->services['prestashop.core.filter.front_end_object.cart'] ?? $this->getPrestashop_Core_Filter_FrontEndObject_CartService()), 'customer' => ($this->services['prestashop.core.filter.front_end_object.customer'] ?? ($this->services['prestashop.core.filter.front_end_object.customer'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\CustomerFilter())), 'shop' => ($this->services['prestashop.core.filter.front_end_object.shop'] ?? ($this->services['prestashop.core.filter.front_end_object.shop'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ShopFilter())), 'configuration' => ($this->services['prestashop.core.filter.front_end_object.configuration'] ?? ($this->services['prestashop.core.filter.front_end_object.configuration'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ConfigurationFilter()))]);
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.product' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ProductFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_ProductService()
    {
        return $this->services['prestashop.core.filter.front_end_object.product'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ProductFilter();
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.product_collection' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\CollectionFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_ProductCollectionService()
    {
        $this->services['prestashop.core.filter.front_end_object.product_collection'] = $instance = new \PrestaShop\PrestaShop\Core\Filter\CollectionFilter();

        $instance->queue([0 => ($this->services['prestashop.core.filter.front_end_object.product'] ?? ($this->services['prestashop.core.filter.front_end_object.product'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ProductFilter()))]);

        return $instance;
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.search_result_product' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\SearchResultProductFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_SearchResultProductService()
    {
        return $this->services['prestashop.core.filter.front_end_object.search_result_product'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\SearchResultProductFilter();
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.search_result_product_collection' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\CollectionFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_SearchResultProductCollectionService()
    {
        $this->services['prestashop.core.filter.front_end_object.search_result_product_collection'] = $instance = new \PrestaShop\PrestaShop\Core\Filter\CollectionFilter();

        $instance->queue([0 => ($this->services['prestashop.core.filter.front_end_object.search_result_product'] ?? ($this->services['prestashop.core.filter.front_end_object.search_result_product'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\SearchResultProductFilter()))]);

        return $instance;
    }

    /**
     * Gets the public 'prestashop.core.filter.front_end_object.shop' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ShopFilter
     */
    protected function getPrestashop_Core_Filter_FrontEndObject_ShopService()
    {
        return $this->services['prestashop.core.filter.front_end_object.shop'] = new \PrestaShop\PrestaShop\Core\Filter\FrontEndObject\ShopFilter();
    }

    /**
     * Gets the public 'prestashop.core.localization.cache.adapter' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\ArrayAdapter
     */
    protected function getPrestashop_Core_Localization_Cache_AdapterService()
    {
        return $this->services['prestashop.core.localization.cache.adapter'] = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
    }

    /**
     * Gets the public 'prestashop.core.localization.cldr.cache.adapter' shared service.
     *
     * @return \Symfony\Component\Cache\Adapter\FilesystemAdapter
     */
    protected function getPrestashop_Core_Localization_Cldr_Cache_AdapterService()
    {
        return $this->services['prestashop.core.localization.cldr.cache.adapter'] = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('CLDR', 0, '/var/www/html/var/cache/prod//localization');
    }

    /**
     * Gets the public 'prestashop.core.localization.cldr.datalayer.locale_cache' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\CLDR\DataLayer\LocaleCache
     */
    protected function getPrestashop_Core_Localization_Cldr_Datalayer_LocaleCacheService()
    {
        $this->services['prestashop.core.localization.cldr.datalayer.locale_cache'] = $instance = new \PrestaShop\PrestaShop\Core\Localization\CLDR\DataLayer\LocaleCache(($this->services['prestashop.core.localization.cldr.cache.adapter'] ?? ($this->services['prestashop.core.localization.cldr.cache.adapter'] = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('CLDR', 0, '/var/www/html/var/cache/prod//localization'))));

        $instance->setLowerLayer(($this->services['prestashop.core.localization.cldr.datalayer.locale_reference'] ?? $this->getPrestashop_Core_Localization_Cldr_Datalayer_LocaleReferenceService()));

        return $instance;
    }

    /**
     * Gets the public 'prestashop.core.localization.cldr.datalayer.locale_reference' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\CLDR\DataLayer\LocaleReference
     */
    protected function getPrestashop_Core_Localization_Cldr_Datalayer_LocaleReferenceService()
    {
        return $this->services['prestashop.core.localization.cldr.datalayer.locale_reference'] = new \PrestaShop\PrestaShop\Core\Localization\CLDR\DataLayer\LocaleReference(($this->services['prestashop.core.localization.cldr.reader'] ?? ($this->services['prestashop.core.localization.cldr.reader'] = new \PrestaShop\PrestaShop\Core\Localization\CLDR\Reader())));
    }

    /**
     * Gets the public 'prestashop.core.localization.cldr.locale_data_source' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\CLDR\LocaleDataSource
     */
    protected function getPrestashop_Core_Localization_Cldr_LocaleDataSourceService()
    {
        return $this->services['prestashop.core.localization.cldr.locale_data_source'] = new \PrestaShop\PrestaShop\Core\Localization\CLDR\LocaleDataSource(($this->services['prestashop.core.localization.cldr.datalayer.locale_cache'] ?? $this->getPrestashop_Core_Localization_Cldr_Datalayer_LocaleCacheService()));
    }

    /**
     * Gets the public 'prestashop.core.localization.cldr.locale_repository' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\CLDR\LocaleRepository
     */
    protected function getPrestashop_Core_Localization_Cldr_LocaleRepositoryService()
    {
        return $this->services['prestashop.core.localization.cldr.locale_repository'] = new \PrestaShop\PrestaShop\Core\Localization\CLDR\LocaleRepository(($this->services['prestashop.core.localization.cldr.locale_data_source'] ?? $this->getPrestashop_Core_Localization_Cldr_LocaleDataSourceService()));
    }

    /**
     * Gets the public 'prestashop.core.localization.cldr.reader' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\CLDR\Reader
     */
    protected function getPrestashop_Core_Localization_Cldr_ReaderService()
    {
        return $this->services['prestashop.core.localization.cldr.reader'] = new \PrestaShop\PrestaShop\Core\Localization\CLDR\Reader();
    }

    /**
     * Gets the public 'prestashop.core.localization.currency.datasource' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Currency\CurrencyDataSource
     */
    protected function getPrestashop_Core_Localization_Currency_DatasourceService()
    {
        return $this->services['prestashop.core.localization.currency.datasource'] = new \PrestaShop\PrestaShop\Core\Localization\Currency\CurrencyDataSource(($this->services['prestashop.core.localization.currency.middleware.cache'] ?? $this->getPrestashop_Core_Localization_Currency_Middleware_CacheService()), ($this->services['prestashop.core.localization.currency.middleware.installed'] ?? $this->getPrestashop_Core_Localization_Currency_Middleware_InstalledService()));
    }

    /**
     * Gets the public 'prestashop.core.localization.currency.middleware.cache' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyCache
     */
    protected function getPrestashop_Core_Localization_Currency_Middleware_CacheService()
    {
        $this->services['prestashop.core.localization.currency.middleware.cache'] = $instance = new \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyCache(($this->services['prestashop.core.localization.cache.adapter'] ?? ($this->services['prestashop.core.localization.cache.adapter'] = new \Symfony\Component\Cache\Adapter\ArrayAdapter())));

        $instance->setLowerLayer(($this->services['prestashop.core.localization.currency.middleware.database'] ?? $this->getPrestashop_Core_Localization_Currency_Middleware_DatabaseService()));

        return $instance;
    }

    /**
     * Gets the public 'prestashop.core.localization.currency.middleware.database' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyDatabase
     */
    protected function getPrestashop_Core_Localization_Currency_Middleware_DatabaseService()
    {
        $this->services['prestashop.core.localization.currency.middleware.database'] = $instance = new \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyDatabase(($this->services['PrestaShop\\PrestaShop\\Adapter\\Currency\\CurrencyDataProvider'] ?? $this->getCurrencyDataProviderService()));

        $instance->setLowerLayer(($this->services['prestashop.core.localization.currency.middleware.reference'] ?? $this->getPrestashop_Core_Localization_Currency_Middleware_ReferenceService()));

        return $instance;
    }

    /**
     * Gets the public 'prestashop.core.localization.currency.middleware.installed' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyInstalled
     */
    protected function getPrestashop_Core_Localization_Currency_Middleware_InstalledService()
    {
        return $this->services['prestashop.core.localization.currency.middleware.installed'] = new \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyInstalled(($this->services['PrestaShop\\PrestaShop\\Adapter\\Currency\\CurrencyDataProvider'] ?? $this->getCurrencyDataProviderService()));
    }

    /**
     * Gets the public 'prestashop.core.localization.currency.middleware.reference' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyReference
     */
    protected function getPrestashop_Core_Localization_Currency_Middleware_ReferenceService()
    {
        return $this->services['prestashop.core.localization.currency.middleware.reference'] = new \PrestaShop\PrestaShop\Core\Localization\Currency\DataLayer\CurrencyReference(($this->services['prestashop.core.localization.cldr.locale_repository'] ?? $this->getPrestashop_Core_Localization_Cldr_LocaleRepositoryService()));
    }

    /**
     * Gets the public 'prestashop.core.localization.currency.repository' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Currency\Repository
     */
    protected function getPrestashop_Core_Localization_Currency_RepositoryService()
    {
        return $this->services['prestashop.core.localization.currency.repository'] = new \PrestaShop\PrestaShop\Core\Localization\Currency\Repository(($this->services['prestashop.core.localization.currency.datasource'] ?? $this->getPrestashop_Core_Localization_Currency_DatasourceService()));
    }

    /**
     * Gets the public 'prestashop.core.localization.locale.context_locale' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\Localization\Locale
     */
    protected function getPrestashop_Core_Localization_Locale_ContextLocaleService()
    {
        return $this->services['prestashop.core.localization.locale.context_locale'] = ($this->services['PrestaShop\\PrestaShop\\Core\\Localization\\Locale\\Repository'] ?? $this->getRepositoryService())->getLocale(($this->services['PrestaShop\\PrestaShop\\Adapter\\LegacyContext'] ?? $this->getLegacyContextService())->getContext()->language->getLocale());
    }

    /**
     * Gets the public 'prestashop.core.string.character_cleaner' shared service.
     *
     * @return \PrestaShop\PrestaShop\Core\String\CharacterCleaner
     *
     * @deprecated The "prestashop.core.string.character_cleaner" service is deprecated. You should stop using it, as it will be removed in the future.
     */
    protected function getPrestashop_Core_String_CharacterCleanerService()
    {
        @trigger_error('The "prestashop.core.string.character_cleaner" service is deprecated. You should stop using it, as it will be removed in the future.', E_USER_DEPRECATED);

        return $this->services['prestashop.core.string.character_cleaner'] = new \PrestaShop\PrestaShop\Core\String\CharacterCleaner();
    }

    /**
     * Gets the public 'prestashop.database.naming_strategy' shared service.
     *
     * @return \PrestaShopBundle\Service\Database\DoctrineNamingStrategy
     */
    protected function getPrestashop_Database_NamingStrategyService()
    {
        return $this->services['prestashop.database.naming_strategy'] = new \PrestaShopBundle\Service\Database\DoctrineNamingStrategy('ps_');
    }

    /**
     * Gets the public 'prestashop.translation.translator_language_loader' shared service.
     *
     * @return \PrestaShopBundle\Translation\TranslatorLanguageLoader
     */
    protected function getPrestashop_Translation_TranslatorLanguageLoaderService()
    {
        return $this->services['prestashop.translation.translator_language_loader'] = new \PrestaShopBundle\Translation\TranslatorLanguageLoader(($this->services['prestashop.adapter.module.repository.module_repository'] ?? ($this->services['prestashop.adapter.module.repository.module_repository'] = new \PrestaShop\PrestaShop\Adapter\Module\Repository\ModuleRepository('/var/www/html', '/var/www/html/modules/'))));
    }

    /**
     * Gets the public 'product_comment_criterion_repository' shared service.
     *
     * @return \PrestaShop\Module\ProductComment\Repository\ProductCommentCriterionRepository
     */
    protected function getProductCommentCriterionRepositoryService()
    {
        return $this->services['product_comment_criterion_repository'] = new \PrestaShop\Module\ProductComment\Repository\ProductCommentCriterionRepository(($this->services['doctrine'] ?? $this->getDoctrineService()), ($this->services['doctrine.dbal.default_connection'] ?? $this->getDoctrine_Dbal_DefaultConnectionService()), 'ps_');
    }

    /**
     * Gets the public 'product_comment_repository' shared service.
     *
     * @return \PrestaShop\Module\ProductComment\Repository\ProductCommentRepository
     */
    protected function getProductCommentRepositoryService()
    {
        return $this->services['product_comment_repository'] = new \PrestaShop\Module\ProductComment\Repository\ProductCommentRepository(($this->services['doctrine'] ?? $this->getDoctrineService()), ($this->services['doctrine.dbal.default_connection'] ?? $this->getDoctrine_Dbal_DefaultConnectionService()), 'ps_', ($this->services['PrestaShop\\PrestaShop\\Adapter\\Configuration'] ?? ($this->services['PrestaShop\\PrestaShop\\Adapter\\Configuration'] = new \PrestaShop\PrestaShop\Adapter\Configuration()))->get("PRODUCT_COMMENTS_ALLOW_GUESTS"), ($this->services['PrestaShop\\PrestaShop\\Adapter\\Configuration'] ?? ($this->services['PrestaShop\\PrestaShop\\Adapter\\Configuration'] = new \PrestaShop\PrestaShop\Adapter\Configuration()))->get("PRODUCT_COMMENTS_MINIMAL_TIME"));
    }

    /**
     * Gets the public 'ps_accounts.facade' shared service.
     *
     * @return \PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts
     */
    protected function getPsAccounts_FacadeService()
    {
        return $this->services['ps_accounts.facade'] = new \PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts(($this->services['ps_accounts.installer'] ?? ($this->services['ps_accounts.installer'] = new \PrestaShop\PsAccountsInstaller\Installer\Installer('5.0'))));
    }

    /**
     * Gets the public 'ps_accounts.installer' shared service.
     *
     * @return \PrestaShop\PsAccountsInstaller\Installer\Installer
     */
    protected function getPsAccounts_InstallerService()
    {
        return $this->services['ps_accounts.installer'] = new \PrestaShop\PsAccountsInstaller\Installer\Installer('5.0');
    }

    /**
     * Gets the public 'ps_checkout.db' shared service.
     *
     * @return \Db
     */
    protected function getPsCheckout_DbService()
    {
        return $this->services['ps_checkout.db'] = \Db::getInstance();
    }

    /**
     * Gets the public 'ps_checkout.module' shared service.
     *
     * @return \ps_checkout
     */
    protected function getPsCheckout_ModuleService()
    {
        return $this->services['ps_checkout.module'] = \Module::getInstanceByName('ps_checkout');
    }

    /**
     * Gets the public 'ps_facebook' shared service.
     *
     * @return \Ps_facebook
     */
    protected function getPsFacebookService()
    {
        return $this->services['ps_facebook'] = \Module::getInstanceByName('ps_facebook');
    }

    /**
     * Gets the public 'ps_facebook.billing_env' shared service.
     *
     * @return \PrestaShop\Module\PrestashopFacebook\Factory\ParametersFactory
     */
    protected function getPsFacebook_BillingEnvService()
    {
        return $this->services['ps_facebook.billing_env'] = \PrestaShop\Module\PrestashopFacebook\Factory\ParametersFactory::getBillingEnv();
    }

    /**
     * Gets the public 'ps_facebook.cache' shared service.
     *
     * @return \string
     */
    protected function getPsFacebook_CacheService()
    {
        return $this->services['ps_facebook.cache'] = \PrestaShop\Module\PrestashopFacebook\Factory\CacheFactory::getCachePath();
    }

    /**
     * Gets the public 'ps_facebook.context' shared service.
     *
     * @return \Context
     */
    protected function getPsFacebook_ContextService()
    {
        return $this->services['ps_facebook.context'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getContext();
    }

    /**
     * Gets the public 'ps_facebook.controller' shared service.
     *
     * @return \Controller
     */
    protected function getPsFacebook_ControllerService()
    {
        return $this->services['ps_facebook.controller'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getController();
    }

    /**
     * Gets the public 'ps_facebook.cookie' shared service.
     *
     * @return \Cookie
     */
    protected function getPsFacebook_CookieService()
    {
        return $this->services['ps_facebook.cookie'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getCookie();
    }

    /**
     * Gets the public 'ps_facebook.currency' shared service.
     *
     * @return \Currency
     */
    protected function getPsFacebook_CurrencyService()
    {
        return $this->services['ps_facebook.currency'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getCurrency();
    }

    /**
     * Gets the public 'ps_facebook.language' shared service.
     *
     * @return \Language
     */
    protected function getPsFacebook_LanguageService()
    {
        return $this->services['ps_facebook.language'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getLanguage();
    }

    /**
     * Gets the public 'ps_facebook.link' shared service.
     *
     * @return \Shop
     */
    protected function getPsFacebook_LinkService()
    {
        return $this->services['ps_facebook.link'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getLink();
    }

    /**
     * Gets the public 'ps_facebook.shop' shared service.
     *
     * @return \Shop
     */
    protected function getPsFacebook_ShopService()
    {
        return $this->services['ps_facebook.shop'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getShop();
    }

    /**
     * Gets the public 'ps_facebook.smarty' shared service.
     *
     * @return \Smarty
     */
    protected function getPsFacebook_SmartyService()
    {
        return $this->services['ps_facebook.smarty'] = \PrestaShop\Module\PrestashopFacebook\Factory\ContextFactory::getSmarty();
    }

    /**
     * Gets the public 'psshipping' shared service.
     *
     * @return \Psshipping
     */
    protected function getPsshippingService()
    {
        return $this->services['psshipping'] = \Module::getInstanceByName('psshipping');
    }

    /**
     * Gets the public 'psshipping.context' shared service.
     *
     * @return \Context
     */
    protected function getPsshipping_ContextService()
    {
        return $this->services['psshipping.context'] = \Context::getContext();
    }

    /**
     * Gets the public 'psshipping.helper.config' shared service.
     *
     * @return \PrestaShop\Module\Psshipping\Helper\ConfigHelper
     */
    protected function getPsshipping_Helper_ConfigService()
    {
        return $this->services['psshipping.helper.config'] = new \PrestaShop\Module\Psshipping\Helper\ConfigHelper('https://shipping-api.prestashop.com', 'https://assets.prestashop3.com/shipping', 0, 0, 'https://www.mbe.it/en/tracking?c=@', '3XsHeI2dfKoKE2wReGp7IO2bLa5hbeVB', 'https://78c41abf489931010a3a83cacc14926b@o298402.ingest.sentry.io/4505906299600896', 'production');
    }

    /**
     * Gets the public 'psshipping.ps_billings_context_wrapper' shared service.
     *
     * @return \PrestaShopCorp\Billing\Wrappers\BillingContextWrapper
     */
    protected function getPsshipping_PsBillingsContextWrapperService()
    {
        return $this->services['psshipping.ps_billings_context_wrapper'] = new \PrestaShopCorp\Billing\Wrappers\BillingContextWrapper(($this->services['ps_accounts.facade'] ?? $this->getPsAccounts_FacadeService()), ($this->services['psshipping.context'] ?? $this->getPsshipping_ContextService()), 0);
    }

    /**
     * Gets the public 'psshipping.ps_billings_facade' shared service.
     *
     * @return \PrestaShopCorp\Billing\Presenter\BillingPresenter
     */
    protected function getPsshipping_PsBillingsFacadeService()
    {
        return $this->services['psshipping.ps_billings_facade'] = new \PrestaShopCorp\Billing\Presenter\BillingPresenter(($this->services['psshipping.ps_billings_context_wrapper'] ?? $this->getPsshipping_PsBillingsContextWrapperService()), ($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'psshipping.ps_billings_service' shared service.
     *
     * @return \PrestaShopCorp\Billing\Services\BillingService
     */
    protected function getPsshipping_PsBillingsServiceService()
    {
        return $this->services['psshipping.ps_billings_service'] = new \PrestaShopCorp\Billing\Services\BillingService(($this->services['psshipping.ps_billings_context_wrapper'] ?? $this->getPsshipping_PsBillingsContextWrapperService()), ($this->services['psshipping'] ?? $this->getPsshippingService()));
    }

    /**
     * Gets the public 'psxmarketingwithgoogle' shared service.
     *
     * @return \PsxMarketingWithGoogle
     */
    protected function getPsxmarketingwithgoogleService()
    {
        return $this->services['psxmarketingwithgoogle'] = \Module::getInstanceByName('psxmarketingwithgoogle');
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.billing_env' shared service.
     *
     * @return \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ParametersFactory
     */
    protected function getPsxmarketingwithgoogle_BillingEnvService()
    {
        return $this->services['psxmarketingwithgoogle.billing_env'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ParametersFactory::getBillingEnv();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.cart' shared service.
     *
     * @return \Currency
     */
    protected function getPsxmarketingwithgoogle_CartService()
    {
        return $this->services['psxmarketingwithgoogle.cart'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getCart();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.context' shared service.
     *
     * @return \Context
     */
    protected function getPsxmarketingwithgoogle_ContextService()
    {
        return $this->services['psxmarketingwithgoogle.context'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getContext();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.controller' shared service.
     *
     * @return \Controller
     */
    protected function getPsxmarketingwithgoogle_ControllerService()
    {
        return $this->services['psxmarketingwithgoogle.controller'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getController();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.cookie' shared service.
     *
     * @return \Cookie
     */
    protected function getPsxmarketingwithgoogle_CookieService()
    {
        return $this->services['psxmarketingwithgoogle.cookie'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getCookie();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.country' shared service.
     *
     * @return \Country
     */
    protected function getPsxmarketingwithgoogle_CountryService()
    {
        return $this->services['psxmarketingwithgoogle.country'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getCountry();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.currency' shared service.
     *
     * @return \Currency
     */
    protected function getPsxmarketingwithgoogle_CurrencyService()
    {
        return $this->services['psxmarketingwithgoogle.currency'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getCurrency();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.customer' shared service.
     *
     * @return \Currency
     */
    protected function getPsxmarketingwithgoogle_CustomerService()
    {
        return $this->services['psxmarketingwithgoogle.customer'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getCustomer();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.db' shared service.
     *
     * @return \Db
     */
    protected function getPsxmarketingwithgoogle_DbService()
    {
        return $this->services['psxmarketingwithgoogle.db'] = \Db::getInstance();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.language' shared service.
     *
     * @return \Language
     */
    protected function getPsxmarketingwithgoogle_LanguageService()
    {
        return $this->services['psxmarketingwithgoogle.language'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getLanguage();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.link' shared service.
     *
     * @return \Shop
     */
    protected function getPsxmarketingwithgoogle_LinkService()
    {
        return $this->services['psxmarketingwithgoogle.link'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getLink();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.shop' shared service.
     *
     * @return \Shop
     */
    protected function getPsxmarketingwithgoogle_ShopService()
    {
        return $this->services['psxmarketingwithgoogle.shop'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getShop();
    }

    /**
     * Gets the public 'psxmarketingwithgoogle.smarty' shared service.
     *
     * @return \Smarty
     */
    protected function getPsxmarketingwithgoogle_SmartyService()
    {
        return $this->services['psxmarketingwithgoogle.smarty'] = \PrestaShop\Module\PsxMarketingWithGoogle\Factory\ContextFactory::getSmarty();
    }

    /**
     * Gets the private 'PrestaShopBundle\DependencyInjection\RuntimeConstEnvVarProcessor' shared service.
     *
     * @return \PrestaShopBundle\DependencyInjection\RuntimeConstEnvVarProcessor
     */
    protected function getRuntimeConstEnvVarProcessorService()
    {
        return $this->privates['PrestaShopBundle\\DependencyInjection\\RuntimeConstEnvVarProcessor'] = new \PrestaShopBundle\DependencyInjection\RuntimeConstEnvVarProcessor();
    }

    /**
     * Gets the private 'PrestaShopCorp\Billing\Wrappers\BillingContextWrapper' shared service.
     *
     * @return \PrestaShopCorp\Billing\Wrappers\BillingContextWrapper
     */
    protected function getBillingContextWrapperService()
    {
        return $this->privates['PrestaShopCorp\\Billing\\Wrappers\\BillingContextWrapper'] = new \PrestaShopCorp\Billing\Wrappers\BillingContextWrapper(($this->services['PrestaShop\\PsAccountsInstaller\\Installer\\Facade\\PsAccounts'] ?? $this->getPsAccountsService()), ($this->services['ps_facebook.context'] ?? $this->getPsFacebook_ContextService()), ($this->services['ps_facebook.billing_env'] ?? $this->getPsFacebook_BillingEnvService()));
    }

    /**
     * Gets the public 'prestashop.adapter.tools' alias.
     *
     * @return object The "PrestaShop\PrestaShop\Adapter\Tools" service.
     */
    protected function getPrestashop_Adapter_ToolsService()
    {
        @trigger_error('The "prestashop.adapter.tools" service alias is deprecated. You should stop using it, as it will be removed in the future.', E_USER_DEPRECATED);

        return $this->get('PrestaShop\\PrestaShop\\Adapter\\Tools');
    }

    /**
     * @return array|bool|float|int|string|\UnitEnum|null
     */
    public function getParameter($name)
    {
        $name = (string) $name;

        if (!(isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters))) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }
        if (isset($this->loadedDynamicParameters[$name])) {
            return $this->loadedDynamicParameters[$name] ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
        }

        return $this->parameters[$name];
    }

    public function hasParameter($name): bool
    {
        $name = (string) $name;

        return isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters);
    }

    public function setParameter($name, $value): void
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    public function getParameterBag(): ParameterBagInterface
    {
        if (null === $this->parameterBag) {
            $parameters = $this->parameters;
            foreach ($this->loadedDynamicParameters as $name => $loaded) {
                $parameters[$name] = $loaded ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
            }
            $this->parameterBag = new FrozenParameterBag($parameters);
        }

        return $this->parameterBag;
    }

    private $loadedDynamicParameters = [];
    private $dynamicParameters = [];

    private function getDynamicParameter(string $name)
    {
        throw new InvalidArgumentException(sprintf('The dynamic parameter "%s" must be defined.', $name));
    }

    protected function getDefaultParameters(): array
    {
        return [
            'database_host' => 'db',
            'database_port' => '',
            'database_name' => 'prestashop',
            'database_user' => 'ps_user',
            'database_password' => 'ps_pass',
            'database_prefix' => 'ps_',
            'database_engine' => 'InnoDB',
            'mailer_transport' => 'smtp',
            'mailer_host' => '127.0.0.1',
            'mailer_user' => NULL,
            'mailer_password' => NULL,
            'secret' => 'yRXsNWeqE0YE2XxWbw9GRqGtucRpwTxgIpU6RtZEEBKp7kT4zOnicFe9A9bWgL5W',
            'ps_caching' => 'CacheMemcache',
            'ps_cache_enable' => false,
            'ps_creation_date' => '2026-05-08',
            'locale' => 'fr-FR',
            'use_debug_toolbar' => true,
            'cookie_key' => 'yrCwhjyHQCTcpmrWtHX2L9OLCW2wwmFEPXWXRUMhUZ3o8TEwYWfnUtBuoEFiDSg9',
            'cookie_iv' => 'ajGKf9M3GhoLvFq9mwbj8IquvOKKDS2e',
            'new_cookie_key' => 'def0000006fef695835ab69de534a0b3c08f1d1b475802f4f176261f28a14919da1b8c118c1e092837cfd2f5c446fa3d1d74d5bc47f1a0c7fa12a03a6961cc512e927b7b',
            'api_public_key' => '-----BEGIN PUBLIC KEY-----'."\n".'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA01FwYLdCZI2FPEhKHaBq'."\n".'3GCOJANgMB2eJjzffonq2OW8SrTIYOssYDI0THgxv9SBEaj2kCFPykHYc/VYSNlQ'."\n".'6weopgiMIef1W7F6EJ6twknL4Yz2fctpve/4dW30c79sX8kFnCpo3lcf59wAkJxA'."\n".'Mr9ey8S1kDyOGORrSKXz7iLC0ZTbUSZqNqLQeoWHa5TMjTIdEfMFoJmNGxB3CikO'."\n".'19ddBEkcG8lIyrZXWqWBAxuBCF3sf3I4UjZhXwtcLzySMzXv7p6RaHlEvsmIqsTl'."\n".'G/Fe/FNf/wsB6Ddv3qxJvmF/NlWc3dMhonjqq5hrQ1MeQEJrsNEEXg23M6WIQzry'."\n".'bwIDAQAB'."\n".'-----END PUBLIC KEY-----'."\n".'',
            'api_private_key' => '-----BEGIN PRIVATE KEY-----'."\n".'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDTUXBgt0JkjYU8'."\n".'SEodoGrcYI4kA2AwHZ4mPN9+ierY5bxKtMhg6yxgMjRMeDG/1IERqPaQIU/KQdhz'."\n".'9VhI2VDrB6imCIwh5/VbsXoQnq3CScvhjPZ9y2m97/h1bfRzv2xfyQWcKmjeVx/n'."\n".'3ACQnEAyv17LxLWQPI4Y5GtIpfPuIsLRlNtRJmo2otB6hYdrlMyNMh0R8wWgmY0b'."\n".'EHcKKQ7X110ESRwbyUjKtldapYEDG4EIXex/cjhSNmFfC1wvPJIzNe/unpFoeUS+'."\n".'yYiqxOUb8V78U1//CwHoN2/erEm+YX82VZzd0yGieOqrmGtDUx5AQmuw0QReDbcz'."\n".'pYhDOvJvAgMBAAECggEAOC1hySxe15lxzp1PKsIfNnfqwbMuCoyNIxAywBKNHt0w'."\n".'KdIWpQFQGkCue5fGbf9h0p6O8bERY60OjIEwV6mCJAE0ufLywf7G0PoE/88XVVvF'."\n".'+i+9xaJieY6GiD1Ta8P7XRl1RtdVaqpRvpNTiR4oyPTsEBK6y931TrPGu5j2mJsk'."\n".'TYryBnIa/Ak7BlGZaFb+W0x5o2flhYYNeU37D+HI0QPpSd4s4S6MvKeR/KfZZ5F6'."\n".'8119qQmpDR62bvnFw4ba3WmguIlVmmuNF0G+bHWyiGeSwvV+AvkbqAikdViVFH81'."\n".'0S/BG/mbh/aqsR143l/1bvlhLzNYj4huFYfavSJtmQKBgQD0DJktmpfab2ym3BKu'."\n".'buqli+/vEwLS/x4cNv+SezUR+kPBnC7/0c9vy7zTE1U6YmYmiOZIF9NDZA4maANM'."\n".'+IGnR5YOIV/09kepSVFfkZ6Bho9IWBRFDtT6Qg7i4fiyn8sN0QBLeKpMSn+IsLkt'."\n".'sS+YzLPIIRj9H4AVpTx9dG6ZFwKBgQDdqoYIbQkKoTBIwuqa1XEBgoGbD6i8frzs'."\n".'WVYs4AyvUqFRsR9hwzqcbuJhJmycmBmBTlyvtCmp13demCMMUnSPCYERSCYT+aFS'."\n".'p5ljN0jrTqV6eEAAgUW0wpfzYUOj+apmT51x17rheOKPrE1+l6xnGkgGukVtwZ7k'."\n".'9sXX4xwYaQKBgFtNE0lSWpknslhCYOLNMA+66IpibF4Pf25CzR455cLSA+M80QB3'."\n".'t8k1SXEV/H6ZLWohMTWrZCgEGJ8ZrpJvLeItD+A9cC6urGSSv1Snn8sJUjVYbkzf'."\n".'naH6cKNEssvwqACFPjz96YgdHtU+eCi17v6VJnUqnLSlwj9Wz+iHVJEFAoGAI6je'."\n".'HRblYHdj3CC3OydhIZwMG5TeUUyWOfN5QIjeoYnKd+jlooWUtTvFYVmcGWkCmWGw'."\n".'2XdDSJZoFqik4sOVCrm9sLkJnycGg9SAgYyQR4KEbnpP2YTyYMz5Qub/ya5Ncl4W'."\n".'dwz8fG9FC1XttZLS4ldBBQwMRUbjt/PXCg74IxkCgYEAsEuh5dDAmBlgVxKupZcO'."\n".'NSZPG91yJqY9VYJRQxcfDCnqgFzBNjHNYbYvz2RxMo+qakFJXB1RsYDMjCbDKh2e'."\n".'Ssc4ZI0clcTW3gXOJT5wmraW3FKSdS+Vsx9lAb9r8rDLhqF+nZKAmOB4479Br2eY'."\n".'xvQi0z29IaivEbnnR7xN4a4='."\n".'-----END PRIVATE KEY-----'."\n".'',
            'cache.driver' => 'array',
            'cache.adapter' => 'cache.adapter.array',
            'kernel.bundles' => [

            ],
            'kernel.root_dir' => '/var/www/html/app',
            'kernel.project_dir' => '/var/www/html',
            'kernel.name' => 'app',
            'kernel.debug' => false,
            'kernel.environment' => 'prod',
            'kernel.cache_dir' => '/var/www/html/var/cache/prod/',
            'kernel.active_modules' => [
                0 => 'ps_linklist',
                1 => 'blockreassurance',
                2 => 'blockwishlist',
                3 => 'psgdpr',
                4 => 'ps_contactinfo',
                5 => 'ps_languageselector',
                6 => 'ps_currencyselector',
                7 => 'ps_customersignin',
                8 => 'ps_shoppingcart',
                9 => 'ps_mainmenu',
                10 => 'ps_searchbar',
                11 => 'ps_imageslider',
                12 => 'ps_featuredproducts',
                13 => 'ps_banner',
                14 => 'ps_customtext',
                15 => 'ps_specials',
                16 => 'ps_newproducts',
                17 => 'ps_bestsellers',
                18 => 'ps_emailsubscription',
                19 => 'ps_socialfollow',
                20 => 'ps_customeraccountlinks',
                21 => 'productcomments',
                22 => 'ps_categorytree',
                23 => 'contactform',
                24 => 'ps_sharebuttons',
                25 => 'dashactivity',
                26 => 'dashgoals',
                27 => 'dashproducts',
                28 => 'dashtrends',
                29 => 'gamification',
                30 => 'graphnvd3',
                31 => 'gridhtml',
                32 => 'gsitemap',
                33 => 'klaviyopsautomation',
                34 => 'pagesnotfound',
                35 => 'psassistant',
                36 => 'psshipping',
                37 => 'psxmarketingwithgoogle',
                38 => 'ps_accounts',
                39 => 'ps_brandlist',
                40 => 'ps_cashondelivery',
                41 => 'ps_categoryproducts',
                42 => 'ps_checkout',
                43 => 'ps_checkpayment',
                44 => 'ps_crossselling',
                45 => 'ps_dataprivacy',
                46 => 'ps_distributionapiclient',
                47 => 'ps_edition_basic',
                48 => 'ps_emailalerts',
                49 => 'ps_eventbus',
                50 => 'ps_facebook',
                51 => 'ps_faviconnotificationbo',
                52 => 'ps_googleanalytics',
                53 => 'ps_mbo',
                54 => 'ps_supplierlist',
                55 => 'ps_themecusto',
                56 => 'ps_viewedproduct',
                57 => 'ps_wirepayment',
                58 => 'statsbestcategories',
                59 => 'statsbestcustomers',
                60 => 'statsbestmanufacturers',
                61 => 'statsbestproducts',
                62 => 'statsbestsuppliers',
                63 => 'statsbestvouchers',
                64 => 'statscarrier',
                65 => 'statscatalog',
                66 => 'statscheckup',
                67 => 'statsdata',
                68 => 'statsforecast',
                69 => 'statsnewsletter',
                70 => 'statspersonalinfos',
                71 => 'statsproduct',
                72 => 'statsregistrations',
                73 => 'statssales',
                74 => 'statssearch',
                75 => 'statsstock',
                76 => 'ps_facetedsearch',
            ],
            'ps_cache_dir' => '/var/www/html/var/cache/prod/',
            'mail_themes_uri' => '/mails/themes',
            'doctrine.dbal.logger.chain.class' => 'Doctrine\\DBAL\\Logging\\LoggerChain',
            'doctrine.dbal.logger.profiling.class' => 'Doctrine\\DBAL\\Logging\\DebugStack',
            'doctrine.dbal.logger.class' => 'Symfony\\Bridge\\Doctrine\\Logger\\DbalLogger',
            'doctrine.dbal.configuration.class' => 'Doctrine\\DBAL\\Configuration',
            'doctrine.data_collector.class' => 'Doctrine\\Bundle\\DoctrineBundle\\DataCollector\\DoctrineDataCollector',
            'doctrine.dbal.connection.event_manager.class' => 'Symfony\\Bridge\\Doctrine\\ContainerAwareEventManager',
            'doctrine.dbal.connection_factory.class' => 'Doctrine\\Bundle\\DoctrineBundle\\ConnectionFactory',
            'doctrine.dbal.events.mysql_session_init.class' => 'Doctrine\\DBAL\\Event\\Listeners\\MysqlSessionInit',
            'doctrine.dbal.events.oracle_session_init.class' => 'Doctrine\\DBAL\\Event\\Listeners\\OracleSessionInit',
            'doctrine.class' => 'Doctrine\\Bundle\\DoctrineBundle\\Registry',
            'doctrine.entity_managers' => [
                'default' => 'doctrine.orm.default_entity_manager',
            ],
            'doctrine.default_entity_manager' => 'default',
            'doctrine.dbal.connection_factory.types' => [

            ],
            'doctrine.connections' => [
                'default' => 'doctrine.dbal.default_connection',
            ],
            'doctrine.default_connection' => 'default',
            'doctrine.orm.configuration.class' => 'Doctrine\\ORM\\Configuration',
            'doctrine.orm.entity_manager.class' => 'Doctrine\\ORM\\EntityManager',
            'doctrine.orm.manager_configurator.class' => 'Doctrine\\Bundle\\DoctrineBundle\\ManagerConfigurator',
            'doctrine.orm.cache.array.class' => 'Doctrine\\Common\\Cache\\ArrayCache',
            'doctrine.orm.cache.apc.class' => 'Doctrine\\Common\\Cache\\ApcCache',
            'doctrine.orm.cache.memcache.class' => 'Doctrine\\Common\\Cache\\MemcacheCache',
            'doctrine.orm.cache.memcache_host' => 'localhost',
            'doctrine.orm.cache.memcache_port' => 11211,
            'doctrine.orm.cache.memcache_instance.class' => 'Memcache',
            'doctrine.orm.cache.memcached.class' => 'Doctrine\\Common\\Cache\\MemcachedCache',
            'doctrine.orm.cache.memcached_host' => 'localhost',
            'doctrine.orm.cache.memcached_port' => 11211,
            'doctrine.orm.cache.memcached_instance.class' => 'Memcached',
            'doctrine.orm.cache.redis.class' => 'Doctrine\\Common\\Cache\\RedisCache',
            'doctrine.orm.cache.redis_host' => 'localhost',
            'doctrine.orm.cache.redis_port' => 6379,
            'doctrine.orm.cache.redis_instance.class' => 'Redis',
            'doctrine.orm.cache.xcache.class' => 'Doctrine\\Common\\Cache\\XcacheCache',
            'doctrine.orm.cache.wincache.class' => 'Doctrine\\Common\\Cache\\WinCacheCache',
            'doctrine.orm.cache.zenddata.class' => 'Doctrine\\Common\\Cache\\ZendDataCache',
            'doctrine.orm.metadata.driver_chain.class' => 'Doctrine\\Persistence\\Mapping\\Driver\\MappingDriverChain',
            'doctrine.orm.metadata.annotation.class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
            'doctrine.orm.metadata.xml.class' => 'Doctrine\\ORM\\Mapping\\Driver\\SimplifiedXmlDriver',
            'doctrine.orm.metadata.yml.class' => 'Doctrine\\ORM\\Mapping\\Driver\\SimplifiedYamlDriver',
            'doctrine.orm.metadata.php.class' => 'Doctrine\\ORM\\Mapping\\Driver\\PHPDriver',
            'doctrine.orm.metadata.staticphp.class' => 'Doctrine\\ORM\\Mapping\\Driver\\StaticPHPDriver',
            'doctrine.orm.metadata.attribute.class' => 'Doctrine\\ORM\\Mapping\\Driver\\AttributeDriver',
            'doctrine.orm.proxy_cache_warmer.class' => 'Symfony\\Bridge\\Doctrine\\CacheWarmer\\ProxyCacheWarmer',
            'form.type_guesser.doctrine.class' => 'Symfony\\Bridge\\Doctrine\\Form\\DoctrineOrmTypeGuesser',
            'doctrine.orm.validator.unique.class' => 'Symfony\\Bridge\\Doctrine\\Validator\\Constraints\\UniqueEntityValidator',
            'doctrine.orm.validator_initializer.class' => 'Symfony\\Bridge\\Doctrine\\Validator\\DoctrineInitializer',
            'doctrine.orm.security.user.provider.class' => 'Symfony\\Bridge\\Doctrine\\Security\\User\\EntityUserProvider',
            'doctrine.orm.listeners.resolve_target_entity.class' => 'Doctrine\\ORM\\Tools\\ResolveTargetEntityListener',
            'doctrine.orm.listeners.attach_entity_listeners.class' => 'Doctrine\\ORM\\Tools\\AttachEntityListenersListener',
            'doctrine.orm.naming_strategy.default.class' => 'Doctrine\\ORM\\Mapping\\DefaultNamingStrategy',
            'doctrine.orm.naming_strategy.underscore.class' => 'Doctrine\\ORM\\Mapping\\UnderscoreNamingStrategy',
            'doctrine.orm.quote_strategy.default.class' => 'Doctrine\\ORM\\Mapping\\DefaultQuoteStrategy',
            'doctrine.orm.quote_strategy.ansi.class' => 'Doctrine\\ORM\\Mapping\\AnsiQuoteStrategy',
            'doctrine.orm.entity_listener_resolver.class' => 'Doctrine\\Bundle\\DoctrineBundle\\Mapping\\ContainerEntityListenerResolver',
            'doctrine.orm.second_level_cache.default_cache_factory.class' => 'Doctrine\\ORM\\Cache\\DefaultCacheFactory',
            'doctrine.orm.second_level_cache.default_region.class' => 'Doctrine\\ORM\\Cache\\Region\\DefaultRegion',
            'doctrine.orm.second_level_cache.filelock_region.class' => 'Doctrine\\ORM\\Cache\\Region\\FileLockRegion',
            'doctrine.orm.second_level_cache.logger_chain.class' => 'Doctrine\\ORM\\Cache\\Logging\\CacheLoggerChain',
            'doctrine.orm.second_level_cache.logger_statistics.class' => 'Doctrine\\ORM\\Cache\\Logging\\StatisticsCacheLogger',
            'doctrine.orm.second_level_cache.cache_configuration.class' => 'Doctrine\\ORM\\Cache\\CacheConfiguration',
            'doctrine.orm.second_level_cache.regions_configuration.class' => 'Doctrine\\ORM\\Cache\\RegionsConfiguration',
            'doctrine.orm.auto_generate_proxy_classes' => false,
            'doctrine.orm.proxy_dir' => '/var/www/html/var/cache/prod//doctrine/orm/Proxies',
            'doctrine.orm.proxy_namespace' => 'Proxies',
            'psshipping.sentry_dsn' => 'https://78c41abf489931010a3a83cacc14926b@o298402.ingest.sentry.io/4505906299600896',
            'psshipping.sentry_env' => 'production',
            'psshipping.ps_billing_sandbox' => 0,
            'psshipping.vue_app_dev_mode' => 0,
            'psshipping.cdn_url' => 'https://assets.prestashop3.com/shipping',
            'psshipping.api_url' => 'https://shipping-api.prestashop.com',
            'psshipping.cloudsync_cdc_url' => 'https://assets.prestashop3.com/ext/cloudsync-merchant-sync-consent/latest/cloudsync-cdc.js',
            'psshipping.use_local_vue_app' => 0,
            'psshipping.mbe_tracking_url' => 'https://www.mbe.it/en/tracking?c=@',
            'psshipping.segment_key' => '3XsHeI2dfKoKE2wReGp7IO2bLa5hbeVB',
        ];
    }
}
