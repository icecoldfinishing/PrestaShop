<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__ea892a5f687fe8ed91a07be90f82233e */
class __TwigTemplate_a81314ccf76d7f3d3adbc28a282f09f7 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'stylesheets' => [$this, 'block_stylesheets'],
            'extra_stylesheets' => [$this, 'block_extra_stylesheets'],
            'content_header' => [$this, 'block_content_header'],
            'content' => [$this, 'block_content'],
            'content_footer' => [$this, 'block_content_footer'],
            'sidebar_right' => [$this, 'block_sidebar_right'],
            'javascripts' => [$this, 'block_javascripts'],
            'extra_javascripts' => [$this, 'block_extra_javascripts'],
            'translate_javascripts' => [$this, 'block_translate_javascripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"fr\">
<head>
  <meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">
<meta name=\"robots\" content=\"NOFOLLOW, NOINDEX\">

<link rel=\"icon\" type=\"image/x-icon\" href=\"/img/favicon.ico\" />
<link rel=\"apple-touch-icon\" href=\"/img/app_icon.png\" />

<title>Accueil • prestashop</title>

  <script type=\"text/javascript\">
    var help_class_name = 'HOME';
    var iso_user = 'fr';
    var lang_is_rtl = '0';
    var full_language_code = 'fr';
    var full_cldr_language_code = 'fr-FR';
    var country_iso_code = 'FR';
    var _PS_VERSION_ = '8.2.6';
    var roundMode = 2;
    var youEditFieldFor = '';
        var new_order_msg = 'Une nouvelle commande a été passée sur votre boutique.';
    var order_number_msg = 'Numéro de commande : ';
    var total_msg = 'Total : ';
    var from_msg = 'Du ';
    var see_order_msg = 'Afficher cette commande';
    var new_customer_msg = 'Un nouveau client s\\'est inscrit sur votre boutique.';
    var customer_name_msg = 'Nom du client : ';
    var new_msg = 'Un nouveau message a été posté sur votre boutique.';
    var see_msg = 'Lire le message';
    var token = '79d3b1d3d71648fd2c51fa95c640ff4c';
    var currentIndex = 'index.php?controller=HOME';
    var employee_token = 'c32dee6bb6382ffc2c8ddcf88ecb0628';
    var choose_language_translate = 'Choisissez la langue :';
    var default_language = '1';
    var admin_modules_link = '/admin3295/index.php/improve/modules/manage?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg';
    var admin_notification_get_link = '/admin3295/index.php/common/notifications?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg';
    var admin_notification_push_link = adminNotificationPushLink = '/admin3295/index.php/common/notifications/ack?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg';
    var tab_modules_list = '';
    var update_success_msg = 'Mise à jour réussie';
    var search_p";
        // line 43
        echo "roduct_msg = 'Rechercher un produit';
  </script>



<link
      rel=\"preload\"
      href=\"/admin3295/themes/new-theme/public/2d8017489da689caedc1.preload..woff2\"
      as=\"font\"
      crossorigin
    >
      <link href=\"/admin3295/themes/new-theme/public/create_product_default_theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/admin3295/themes/new-theme/public/theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"https://unpkg.com/@prestashopcorp/edition-reskin/dist/back.min.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/chosen/jquery.chosen.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/fancybox/jquery.fancybox.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/blockwishlist/public/backoffice.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/admin3295/themes/default/css/vendor/nv.d3.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/klaviyopsautomation/dist/css/klaviyops-admin-global.b13cfc23.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/psxmarketingwithgoogle/views/css/admin/menu.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/ps_facebook/views/css/admin/menu.css\" rel=\"stylesheet\" type=\"text/css\"/>
  
  <script type=\"text/javascript\">
var baseAdminDir = \"\\/admin3295\\/\";
var baseDir = \"\\/\";
var changeFormLanguageUrl = \"\\/admin3295\\/index.php\\/configure\\/advanced\\/employees\\/change-form-language?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\";
var contextPsAccounts = {\"currentContext\":{\"type\":1,\"id\":1},\"psxName\":\"ps_edition_basic\",\"psIs17\":true,\"psAccountsVersion\":\"8.0.13\",\"psAccountsIsInstalled\":true,\"psAccountsInstallLink\":null,\"psAccountsIsEnabled\":true,\"psAccountsEnableLink\":\"http:\\/\\/localhost:8088\\/admin3295\\/index.php\\/improve\\/modules\\/manage\\/action\\/enable\\/ps_accounts?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\",\"psAccountsIsUptodate\":true,\"psAccountsUpdateLink\":null,\"user\":{\"uuid\"";
        // line 69
        echo ":null,\"email\":null,\"emailIsValidated\":false,\"isSuperAdmin\":true},\"backendUser\":{\"email\":\"rohyamboara@gmail.com\",\"employeeId\":1,\"isSuperAdmin\":true},\"currentShop\":{\"id\":\"1\",\"name\":\"prestashop\",\"domain\":\"localhost:8088\",\"domainSsl\":\"localhost:8088\",\"physicalUri\":\"\\/\",\"virtualUri\":\"\",\"frontUrl\":\"https:\\/\\/localhost:8088\\/\",\"uuid\":null,\"publicKey\":\"[deprecated]\",\"employeeId\":0,\"user\":{\"email\":null,\"emailIsValidated\":false,\"uuid\":null},\"url\":\"http:\\/\\/localhost:8088\\/admin3295\\/index.php?controller=AdminDashboard\",\"isLinkedV4\":false,\"unlinkedAuto\":false,\"multishop\":false,\"moduleName\":\"ps_edition_basic\",\"psVersion\":\"8.2.6\"},\"isShopContext\":true,\"superAdminEmail\":\"rohyamboara@gmail.com\",\"onboardingLink\":\"https:\\/\\/accounts.distribution.prestashop.net?shops=W3siaWQiOiIxIiwibmFtZSI6InByZXN0YXNob3AiLCJkb21haW4iOiJsb2NhbGhvc3Q6ODA4OCIsImRvbWFpblNzbCI6ImxvY2FsaG9zdDo4MDg4IiwicGh5c2ljYWxVcmkiOiJcLyIsInZpcnR1YWxVcmkiOiIiLCJmcm9udFVybCI6Imh0dHBzOlwvXC9sb2NhbGhvc3Q6ODA4OFwvIiwidXVpZCI6bnVsbCwicHVibGljS2V5IjoiW2RlcHJlY2F0ZWRdIiwiZW1wbG95ZWVJZCI6IjEiLCJ1c2VyIjp7ImVtYWlsIjpudWxsLCJlbWFpbElzVmFsaWRhdGVkIjpmYWxzZSwidXVpZCI6bnVsbH0sInVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDg4XC9hZG1pbjMyOTVcL2luZGV4LnBocD9jb250cm9sbGVyPUFkbWluRGFzaGJvYXJkIiwiaXNMaW5rZWRWNCI6ZmFsc2UsInVubGlua2VkQXV0byI6ZmFsc2UsIm11bHRpc2hvcCI6ZmFsc2UsIm1vZHVsZU5hbWUiOiJwc19lZGl0aW9uX2Jhc2ljIiwicHNWZXJzaW9uIjoiOC4yLjYifV0=\",\"ssoResendVerificationEmail\":\"https:\\/\\/auth.prestashop.com\\/account\\/send-verification-email\",\"manageAccountLink\":\"https:\\/\\/auth.prestashop.com\\/login?lang=fr\",\"isOnboardedV4\":false,\"shops\":[{\"id\":\"1\",\"name\":\"Default\",\"shops\":[{\"id\":\"1\",\"name\":\"prestashop\",\"domain\":\"localhost:8088\",\"domainSsl\":\"localhost:8088\",\"physicalUri\":\"\\/\",\"virtualUri\":\"\",\"frontUrl\":\"https:\\/\\/localhost:8088\\/\",\"uuid\":null,\"publicKey\":\"[deprecated]\",\"employeeId\":0,\"user\":{\"email\":null,\"emailIsValidated\":false,\"uuid\":null},\"url\":\"http:\\/\\/localhost:8088\\/admin3295\\/index.php?controller=AdminDashboard\",\"isLinkedV4\":false,\"unlinkedAuto";
        echo "\":false,\"multishop\":false,\"moduleName\":\"ps_edition_basic\",\"psVersion\":\"8.2.6\",\"moduleVersion\":\"8.0.13\"}],\"multishop\":false,\"moduleName\":\"ps_edition_basic\",\"psVersion\":\"8.2.6\"}],\"adminAjaxLink\":\"http:\\/\\/localhost:8088\\/admin3295\\/index.php?controller=AdminAjaxPsAccounts&ajax=1&token=efe64df25a3f54e5027925949d2cd699\",\"accountsUiUrl\":\"https:\\/\\/accounts.distribution.prestashop.net\",\"component_params_init\":{\"mode\":1,\"shopId\":1,\"groupId\":1,\"getContextUrl\":\"http:\\/\\/localhost:8088\\/admin3295\\/index.php?controller=AdminAjaxV2PsAccounts&ajax=1&action=getContext&source=ps_edition_basic\",\"manageAccountUrl\":\"https:\\/\\/accounts.distribution.prestashop.net\",\"token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NzgzMTQ4MDcsImV4cCI6MTc3ODMxODQwN30.fQHxvJr0uBEf6f_FkNB_Vp4JyuA7aYlHIJQ4qo3G6Z4\",\"psxName\":\"ps_edition_basic\"},\"dependencies\":{\"ps_eventbus\":{\"isInstalled\":true,\"installLink\":\"http:\\/\\/localhost:8088\\/admin3295\\/index.php\\/improve\\/modules\\/manage\\/action\\/install\\/ps_eventbus?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\",\"isEnabled\":true,\"enableLink\":\"http:\\/\\/localhost:8088\\/admin3295\\/index.php\\/improve\\/modules\\/manage\\/action\\/enable\\/ps_eventbus?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\"}}};
var currency = {\"iso_code\":\"EUR\",\"sign\":\"\\u20ac\",\"name\":\"Euro\",\"format\":null};
var currency_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"currencyCode\":\"EUR\",\"currencySymbol\":\"\\u20ac\",\"numberSymbols\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.00\\u00a0\\u00a4\",\"negativePattern\":\"-#,##0.00\\u00a0\\u00a4\",\"maxFractionDigits\":2,\"minFractionDigits\":2,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var number_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"numberSymbols\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.###\",\"negativePattern\":\"-#,##0.###\",\"maxFrac";
        // line 72
        echo "tionDigits\":3,\"minFractionDigits\":0,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var prestashop = {\"debug\":false};
var ps_edition_basic_favicon = \"\\/modules\\/ps_edition_basic\\/views\\/favicon.png\";
var show_new_customers = \"1\";
var show_new_messages = \"1\";
var show_new_orders = \"1\";
</script>
<script type=\"text/javascript\" src=\"/modules/ps_edition_basic/views/js/favicon.js\"></script>
<script type=\"text/javascript\" src=\"/admin3295/themes/new-theme/public/main.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/jquery.chosen.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/fancybox/jquery.fancybox.js\"></script>
<script type=\"text/javascript\" src=\"/js/admin.js?v=8.2.6\"></script>
<script type=\"text/javascript\" src=\"/admin3295/themes/new-theme/public/cldr.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/tools.js?v=8.2.6\"></script>
<script type=\"text/javascript\" src=\"/admin3295/themes/new-theme/public/create_product.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/modules/blockwishlist/public/vendors.js\"></script>
<script type=\"text/javascript\" src=\"/modules/gamification/views/js/gamification_bt.js\"></script>
<script type=\"text/javascript\" src=\"/js/vendor/d3.v3.min.js\"></script>
<script type=\"text/javascript\" src=\"/admin3295/themes/default/js/vendor/nv.d3.min.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_emailalerts/js/admin/ps_emailalerts.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_mbo/views/js/recommended-modules.js?v=4.14.1\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_accounts/views/js/notifications.js?ctx=http%3A%2F%2Flocalhost%3A8088%2Fadmin3295%2Findex.php%3Fcontroller%3DAdminAjaxPsAccounts%26ajax%3D1%26token%3Defe64df25a3f54e5027925949d2cd699%26action%3DgetNotifications&v=8.0.13\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_faviconnotificationbo/views/js/favico.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps";
        // line 95
        echo "_faviconnotificationbo/views/js/ps_faviconnotificationbo.js\"></script>

  <script>
            var admin_gamification_ajax_url = \"http:\\/\\/localhost:8088\\/admin3295\\/index.php?controller=AdminGamification&token=08e4bbb63e549828bd42f1cdcb27866c\";
            var current_id_tab = 152;
        </script>    <script>
        window.userLocale  = 'fr';
        window.userflow_id = 'ct_55jfryadgneorc45cjqxpbf6o4';
    </script>
    <script type=\"module\" src=\"https://unpkg.com/@prestashopcorp/smb-edition-homepage/dist/assets/index.js\"></script><script>
  if (undefined !== ps_faviconnotificationbo) {
    ps_faviconnotificationbo.initialize({
      backgroundColor: '#DF0067',
      textColor: '#FFFFFF',
      notificationGetUrl: '/admin3295/index.php/common/notifications?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg',
      CHECKBOX_ORDER: 1,
      CHECKBOX_CUSTOMER: 1,
      CHECKBOX_MESSAGE: 1,
      timer: 120000, // Refresh every 2 minutes
    });
  }
</script>


";
        // line 119
        $this->displayBlock('stylesheets', $context, $blocks);
        $this->displayBlock('extra_stylesheets', $context, $blocks);
        echo "</head>";
        echo "

<body
  class=\"lang-fr home\"
  data-base-url=\"/admin3295/index.php\"  data-token=\"w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\">

  <header id=\"header\" class=\"d-print-none\">

    <nav id=\"header_infos\" class=\"main-header\">
      <button class=\"btn btn-primary-reverse onclick btn-lg unbind ajax-spinner\"></button>

            <i class=\"material-icons js-mobile-menu\">menu</i>
      <a id=\"header_logo\" class=\"logo float-left\" href=\"/admin3295/index.php/modules/pseditionbasic/homepage?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\"></a>
      <span id=\"shop_version\">8.2.6</span>

      <div class=\"component\" id=\"quick-access-container\">
        <div class=\"dropdown quick-accesses\">
  <button class=\"btn btn-link btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" id=\"quick_select\">
    Accès rapide
  </button>
  <div class=\"dropdown-menu\">
          <a class=\"dropdown-item quick-row-link \"
         href=\"http://localhost:8088/admin3295/index.php/sell/orders?token=8716efc13fcb6628298d09138120113b\"
                 data-item=\"Commandes\"
      >Commandes</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"http://localhost:8088/admin3295/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=ea9e7bdab007f683520df524abbcc973\"
                 data-item=\"Évaluation du catalogue\"
      >Évaluation du catalogue</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"http://localhost:8088/admin3295/index.php/improve/modules/manage?token=8716efc13fcb6628298d09138120113b\"
                 data-item=\"Modules installés\"
      >Modules installés</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"http://localhost:8088/admin3295/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=7b70f20f356a665d537a1a40329192aa\"
                 data-item=\"Nouveau bon de réduction\"
      >Nouveau bon de réduction</a>
          <a class=\"dropdown-item quick-row-link new";
        // line 156
        echo "-product-button\"
         href=\"http://localhost:8088/admin3295/index.php/sell/catalog/products-v2/create?token=8716efc13fcb6628298d09138120113b\"
                 data-item=\"Nouveau produit\"
      >Nouveau produit</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"http://localhost:8088/admin3295/index.php/sell/catalog/categories/new?token=8716efc13fcb6628298d09138120113b\"
                 data-item=\"Nouvelle catégorie\"
      >Nouvelle catégorie</a>
        <div class=\"dropdown-divider\"></div>
          <a id=\"quick-add-link\"
        class=\"dropdown-item js-quick-link\"
        href=\"#\"
        data-rand=\"60\"
        data-icon=\"\"
        data-method=\"add\"
        data-url=\"index.php/modules/pseditionbasic/homepage\"
        data-post-link=\"http://localhost:8088/admin3295/index.php?controller=AdminQuickAccesses&amp;token=b309c66d79219190415fb30a74817a7a\"
        data-prompt-text=\"Veuillez nommer ce raccourci :\"
        data-link=\"Bienvenue - Liste\"
      >
        <i class=\"material-icons\">add_circle</i>
        Ajouter la page actuelle à l'accès rapide
      </a>
        <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"http://localhost:8088/admin3295/index.php?controller=AdminQuickAccesses&amp;token=b309c66d79219190415fb30a74817a7a\">
      <i class=\"material-icons\">settings</i>
      Gérez vos accès rapides
    </a>
  </div>
</div>
      </div>
      <div class=\"component component-search\" id=\"header-search-container\">
        <div class=\"component-search-body\">
          <div class=\"component-search-top\">
            <form id=\"header_search\"
      class=\"bo_search_form dropdown-form js-dropdown-form collapsed\"
      method=\"post\"
      action=\"/admin3295/index.php?controller=AdminSearch&amp;token=1a01e38f6cfc70ac9eba774f7c1d204b\"
      role=\"search\">
  <input type=\"hidden\" name=\"bo_search_type\" id=\"bo_search_type\" class=\"js-search-type\" />
    <div class=\"input-group\">
    <input type=\"text\" class=\"form-control js-form-search\" id=\"bo_quer";
        // line 196
        echo "y\" name=\"bo_query\" value=\"\" placeholder=\"Rechercher (ex. : référence produit, nom du client, etc.)\" aria-label=\"Barre de recherche\">
    <div class=\"input-group-append\">
      <button type=\"button\" class=\"btn btn-outline-secondary dropdown-toggle js-dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
        Partout
      </button>
      <div class=\"dropdown-menu js-items-list\">
        <a class=\"dropdown-item\" data-item=\"Partout\" href=\"#\" data-value=\"0\" data-placeholder=\"Que souhaitez-vous trouver ?\" data-icon=\"icon-search\"><i class=\"material-icons\">search</i> Partout</a>
        <div class=\"dropdown-divider\"></div>
        <a class=\"dropdown-item\" data-item=\"Catalogue\" href=\"#\" data-value=\"1\" data-placeholder=\"Nom du produit, référence, etc.\" data-icon=\"icon-book\"><i class=\"material-icons\">store_mall_directory</i> Catalogue</a>
        <a class=\"dropdown-item\" data-item=\"Clients par nom\" href=\"#\" data-value=\"2\" data-placeholder=\"Nom\" data-icon=\"icon-group\"><i class=\"material-icons\">group</i> Clients par nom</a>
        <a class=\"dropdown-item\" data-item=\"Clients par adresse IP\" href=\"#\" data-value=\"6\" data-placeholder=\"123.45.67.89\" data-icon=\"icon-desktop\"><i class=\"material-icons\">desktop_mac</i> Clients par adresse IP</a>
        <a class=\"dropdown-item\" data-item=\"Commandes\" href=\"#\" data-value=\"3\" data-placeholder=\"ID commande\" data-icon=\"icon-credit-card\"><i class=\"material-icons\">shopping_basket</i> Commandes</a>
        <a class=\"dropdown-item\" data-item=\"Factures\" href=\"#\" data-value=\"4\" data-placeholder=\"Numéro de facture\" data-icon=\"icon-book\"><i class=\"material-icons\">book</i> Factures</a>
        <a class=\"dropdown-item\" data-item=\"Paniers\" href=\"#\" data-value=\"5\" data-placeholder=\"ID panier\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Paniers</a>
        <a class=\"dropdown-item\" data-item=\"Modules\" href=\"#\" data-value=\"7\" data-placeholder=\"Nom du module\" data-icon=\"icon-puzzle-piece\"";
        // line 210
        echo "><i class=\"material-icons\">extension</i> Modules</a>
      </div>
      <button class=\"btn btn-primary\" type=\"submit\"><span class=\"d-none\">RECHERCHE</span><i class=\"material-icons\">search</i></button>
    </div>
  </div>
</form>

<script type=\"text/javascript\">
 \$(document).ready(function(){
    \$('#bo_query').one('click', function() {
    \$(this).closest('form').removeClass('collapsed');
  });
});
</script>
            <button class=\"component-search-cancel d-none\">Annuler</button>
          </div>

          <div class=\"component-search-quickaccess d-none\">
  <p class=\"component-search-title\">Accès rapide</p>
      <a class=\"dropdown-item quick-row-link\"
       href=\"http://localhost:8088/admin3295/index.php/sell/orders?token=8716efc13fcb6628298d09138120113b\"
             data-item=\"Commandes\"
    >Commandes</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"http://localhost:8088/admin3295/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=ea9e7bdab007f683520df524abbcc973\"
             data-item=\"Évaluation du catalogue\"
    >Évaluation du catalogue</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"http://localhost:8088/admin3295/index.php/improve/modules/manage?token=8716efc13fcb6628298d09138120113b\"
             data-item=\"Modules installés\"
    >Modules installés</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"http://localhost:8088/admin3295/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=7b70f20f356a665d537a1a40329192aa\"
             data-item=\"Nouveau bon de réduction\"
    >Nouveau bon de réduction</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"http://localhost:8088/admin3295/index.php/sell/catalog/products-v2/create?token=8716efc13fcb6628298d09138120113b\"
             data-item=\"Nouveau produit\"
    >Nouveau produit</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"http://localhost:8088/admin3295/index.php/sell/catalog/categories/new?token=8716efc13fcb66";
        // line 250
        echo "28298d09138120113b\"
             data-item=\"Nouvelle catégorie\"
    >Nouvelle catégorie</a>
    <div class=\"dropdown-divider\"></div>
      <a id=\"quick-add-link\"
      class=\"dropdown-item js-quick-link\"
      href=\"#\"
      data-rand=\"21\"
      data-icon=\"\"
      data-method=\"add\"
      data-url=\"index.php/modules/pseditionbasic/homepage\"
      data-post-link=\"http://localhost:8088/admin3295/index.php?controller=AdminQuickAccesses&amp;token=b309c66d79219190415fb30a74817a7a\"
      data-prompt-text=\"Veuillez nommer ce raccourci :\"
      data-link=\"Bienvenue - Liste\"
    >
      <i class=\"material-icons\">add_circle</i>
      Ajouter la page actuelle à l'accès rapide
    </a>
    <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"http://localhost:8088/admin3295/index.php?controller=AdminQuickAccesses&amp;token=b309c66d79219190415fb30a74817a7a\">
    <i class=\"material-icons\">settings</i>
    Gérez vos accès rapides
  </a>
</div>
        </div>

        <div class=\"component-search-background d-none\"></div>
      </div>

      
                      <div class=\"component hide-mobile-sm\" id=\"header-maintenance-mode-container\">
          <a class=\"link shop-state\"
             id=\"maintenance-mode\"
             data-toggle=\"pstooltip\"
             data-placement=\"bottom\"
             data-html=\"true\"
             title=\"          &lt;p class=&quot;text-left text-nowrap&quot;&gt;
            &lt;strong&gt;Votre boutique est en mode maintenance.&lt;/strong&gt;
          &lt;/p&gt;
          &lt;p class=&quot;text-left&quot;&gt;
              Vos visiteurs et clients ne peuvent pas accéder à votre boutique lorsque le mode maintenance est activé.
          &lt;/p&gt;
          &lt;p class=&quot;text-left&quot;&gt;
              Pour gérer les paramètres de maintenance, rendez-vous sur la page Paramètres de la boutique &amp;gt; Paramètres généraux &amp;gt; Maintenance.
          &lt;/p&gt;
                      &lt;p class=&quot;text-left&quot;&gt;
           ";
        // line 295
        echo "   Les administrateurs peuvent accéder au front-office de la boutique sans que leur adresse IP ne soit enregistrée.
            &lt;/p&gt;
                  \"
             href=\"/admin3295/index.php/configure/shop/maintenance/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\"
          >
            <i class=\"material-icons\"
              style=\"color: var(--green);\"
            >build</i>
            <span>Mode maintenance</span>
          </a>
        </div>
      
      <div class=\"header-right\">
                  <div class=\"component\" id=\"header-shop-list-container\">
              <div class=\"shop-list\">
    <a class=\"link\" id=\"header_shopname\" href=\"http://localhost:8088/\" target= \"_blank\">
      <i class=\"material-icons\">visibility</i>
      <span>Voir ma boutique</span>
    </a>
  </div>
          </div>
                          <div class=\"component header-right-component\" id=\"header-notifications-container\">
            <div id=\"notif\" class=\"notification-center dropdown dropdown-clickable\">
  <button class=\"btn notification js-notification dropdown-toggle\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">notifications_none</i>
    <span id=\"notifications-total\" class=\"count hide\">0</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right js-notifs_dropdown\">
    <div class=\"notifications\">
      <ul class=\"nav nav-tabs\" role=\"tablist\">
                          <li class=\"nav-item\">
            <a
              class=\"nav-link active\"
              id=\"orders-tab\"
              data-toggle=\"tab\"
              data-type=\"order\"
              href=\"#orders-notifications\"
              role=\"tab\"
            >
              Commandes<span id=\"_nb_new_orders_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"customers-tab\"
              data-toggle=\"tab\"
              data-type=\"customer\"
              href=\"#customers-notific";
        // line 343
        echo "ations\"
              role=\"tab\"
            >
              Clients<span id=\"_nb_new_customers_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"messages-tab\"
              data-toggle=\"tab\"
              data-type=\"customer_message\"
              href=\"#messages-notifications\"
              role=\"tab\"
            >
              Messages<span id=\"_nb_new_messages_\"></span>
            </a>
          </li>
                        </ul>

      <!-- Tab panes -->
      <div class=\"tab-content\">
                          <div class=\"tab-pane active empty\" id=\"orders-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Pas de nouvelle commande pour le moment :(<br>
              Avez-vous consulté vos <strong><a href=\"http://localhost:8088/admin3295/index.php?controller=AdminCarts&action=filterOnlyAbandonedCarts&token=40ef7dafa61db7728abfd8539d5c3072\">paniers abandonnés</a></strong> ?<br> Votre prochaine commande s'y trouve peut-être !
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"customers-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Aucun nouveau client pour l'instant :(<br>
              Êtes-vous actifs sur les réseaux sociaux en ce moment ?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"messages-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Pas de nouveau message pour l'instant.<br>
              On dirait que vos clients sont satisfaits :)
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                        </div>
    </div>
  </div>
</div>

  <script type=\"text/html\" id=\"o";
        // line 391
        echo "rder-notification-template\">
    <a class=\"notif\" href='order_url'>
      #_id_order_ -
      de <strong>_customer_name_</strong> (_iso_code_)_carrier_
      <strong class=\"float-sm-right\">_total_paid_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"customer-notification-template\">
    <a class=\"notif\" href='customer_url'>
      #_id_customer_ - <strong>_customer_name_</strong>_company_ - enregistré le <strong>_date_add_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"message-notification-template\">
    <a class=\"notif\" href='message_url'>
    <span class=\"message-notification-status _status_\">
      <i class=\"material-icons\">fiber_manual_record</i> _status_
    </span>
      - <strong>_customer_name_</strong> (_company_) - <i class=\"material-icons\">access_time</i> _date_add_
    </a>
  </script>
          </div>
        
        <div class=\"component\" id=\"header-employee-container\">
          <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"employee-wrapper-avatar\">
      <div class=\"employee-top\">
        <span class=\"employee-avatar\"><img class=\"avatar rounded-circle\" src=\"http://localhost:8088/img/pr/default.jpg\" alt=\"Rohy\" /></span>
        <span class=\"employee_profile\">Ravi de vous revoir Rohy</span>
      </div>

      <a class=\"dropdown-item employee-link profile-link\" href=\"/admin3295/index.php/configure/advanced/employees/1/edit?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\">
      <i class=\"material-icons\">edit</i>
      <span>Votre profil</span>
    </a>
    </div>

    <p class=\"divider\"></p>

                  <a class=\"dropdown-item \" href=\"https://accounts.distribution.prestashop.net?utm_source=localhost%3A8088&utm_medium=back-office&utm_campaign=ps_accounts&utm_content=headeremployeedropdownlink\"  target=\"_blank\" rel=\"noopener noreferrer nof";
        // line 435
        echo "ollow\">
            <i class=\"material-icons\">open_in_new</i> Manage your PrestaShop account
        </a>
                          <a class=\"dropdown-item ps_mbo\" href=\"https://www.prestashop.com/fr/formation?utm_source=back-office&utm_medium=menu&utm_content=download8_2&utm_campaign=training-fr\"  target=\"_blank\" rel=\"noopener noreferrer nofollow\">
            <i class=\"material-icons\">school</i> Formation
        </a>
                          <a class=\"dropdown-item ps_mbo\" href=\"https://www.prestashop.com/fr/experts?utm_source=back-office&utm_medium=menu&utm_content=download8_2&utm_campaign=expert-fr\"  target=\"_blank\" rel=\"noopener noreferrer nofollow\">
            <i class=\"material-icons\">person_pin_circle</i> Trouver un expert
        </a>
                          <a class=\"dropdown-item ps_mbo\" href=\"/admin3295/index.php/modules/mbo/modules/catalog/?utm_mbo_source=menu-user-back-office&_token=FMfAqJK2vqzls6v3d5iflsEG0xsSnXXsRGYFfV80gjA&utm_source=back-office&utm_medium=menu&utm_content=download8_2&utm_campaign=addons-fr\"  rel=\"noopener noreferrer nofollow\">
            <i class=\"material-icons\">extension</i> Marketplace Prestashop
        </a>
                          <a class=\"dropdown-item ps_mbo\" href=\"https://help-center.prestashop.com/fr?utm_source=back-office&utm_medium=menu&utm_content=download8_2&utm_campaign=help-center-fr\"  target=\"_blank\" rel=\"noopener noreferrer nofollow\">
            <i class=\"material-icons\">help</i> Centre d'assistance
        </a>
                  <p class=\"divider\"></p>
            
    <a class=\"dropdown-item employee-link text-center\" id=\"header_logout\" href=\"http://localhost:8088/admin3295/index.php?controller=AdminLogin&amp;logout=1&amp;token=cce6c95b60f4ec58ea8fff1a9d3535bc\">
      <i class=\"material-icons d-lg-none\">power_settings_new</i>
      <span>Déconnexion</span>
    </a>
  </div>
</div>
        </div>
              </div>
    </nav>
  </header>

  <nav class=\"nav-bar d-none d-print-none d-md-block\">
  <span ";
        // line 464
        echo "class=\"menu-collapse\" data-toggle-url=\"/admin3295/index.php/configure/advanced/employees/toggle-navigation?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\">
    <i class=\"material-icons rtl-flip\">chevron_left</i>
    <i class=\"material-icons rtl-flip\">chevron_left</i>
  </span>

  <div class=\"nav-bar-overflow\">
      <div class=\"logo-container\">
          <a id=\"header_logo\" class=\"logo float-left\" href=\"/admin3295/index.php/modules/pseditionbasic/homepage?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\"></a>
          <span id=\"shop_version\" class=\"header-version\">8.2.6</span>
      </div>

      <ul class=\"main-menu\">
              
                                          
                    
          
            <li class=\"category-title link-active\" data-submenu=\"152\" id=\"tab-HOME\">
                <span class=\"title\">Bienvenue</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"153\" id=\"subtab-AdminPsEditionBasicHomepageController\">
                    <a href=\"/admin3295/index.php/modules/pseditionbasic/homepage?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-home\">home</i>
                      <span>
                      Accueil
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"1\" id=\"subtab-AdminDashboard\">
        ";
        // line 504
        echo "            <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminDashboard&amp;token=28b468c50d9eb1575cf01b8a7acddf1c\" class=\"link\">
                      <i class=\"material-icons mi-trending_up\">trending_up</i>
                      <span>
                      Tableau de bord
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"2\" id=\"tab-SELL\">
                <span class=\"title\">Vendre</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                    <a href=\"/admin3295/index.php/sell/orders/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-shopping_basket\">shopping_basket</i>
                      <span>
                      Commandes
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-3\" class=\"submenu panel-collapse\">
                                                      
                              
                                        ";
        // line 541
        echo "                    
                              <li class=\"link-leveltwo\" data-submenu=\"4\" id=\"subtab-AdminOrders\">
                                <a href=\"/admin3295/index.php/sell/orders/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                                <a href=\"/admin3295/index.php/sell/orders/invoices/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Factures
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                                <a href=\"/admin3295/index.php/sell/orders/credit-slips/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Avoirs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                                <a href=\"/admin3295/index.php/sell/orders/delivery-slips/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Bons de livraison
                                </a>
                              </li>

                                                                                  
                              
 ";
        // line 573
        echo "                                                           
                              <li class=\"link-leveltwo\" data-submenu=\"8\" id=\"subtab-AdminCarts\">
                                <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminCarts&amp;token=40ef7dafa61db7728abfd8539d5c3072\" class=\"link\"> Paniers
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                    <a href=\"/admin3295/index.php/sell/catalog/products?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-store\">store</i>
                      <span>
                      Catalogue
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"10\" id=\"subtab-AdminProducts\">
                                <a href=\"/admin3295/index.php/sell/catalog/products?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Produits
                                </a>
                              </li>

                                                          ";
        // line 604
        echo "                        
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                                <a href=\"/admin3295/index.php/sell/catalog/categories?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Catégories
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                                <a href=\"/admin3295/index.php/sell/catalog/monitoring/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Suivi
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                                <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminAttributesGroups&amp;token=12f031aa9c2055ee547665e9cc9fc266\" class=\"link\"> Attributs &amp; caractéristiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                                <a href=\"/admin3295/index.php/sell/catalog/brands/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Marques et fournisseurs
 ";
        // line 633
        echo "                               </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                                <a href=\"/admin3295/index.php/sell/attachments/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Fichiers
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                                <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminCartRules&amp;token=7b70f20f356a665d537a1a40329192aa\" class=\"link\"> Réductions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"23\" id=\"subtab-AdminStockManagement\">
                                <a href=\"/admin3295/index.php/sell/stocks/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Stock
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
  ";
        // line 667
        echo "                  <a href=\"/admin3295/index.php/sell/customers/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-account_circle\">account_circle</i>
                      <span>
                      Clients
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-24\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"25\" id=\"subtab-AdminCustomers\">
                                <a href=\"/admin3295/index.php/sell/customers/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                                <a href=\"/admin3295/index.php/sell/addresses/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Adresses
                                </a>
                              </li>

                                                                                                                                    </ul>
                                        </li>
                                              
                  
                                                      
                 ";
        // line 698
        echo " 
                  <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
                    <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminCustomerThreads&amp;token=58597b130b9ba510061c9e1d432a78ce\" class=\"link\">
                      <i class=\"material-icons mi-chat\">chat</i>
                      <span>
                      SAV
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-28\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"29\" id=\"subtab-AdminCustomerThreads\">
                                <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminCustomerThreads&amp;token=58597b130b9ba510061c9e1d432a78ce\" class=\"link\"> SAV
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                                <a href=\"/admin3295/index.php/sell/customer-service/order-messages/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Messages prédéfinis
                                </a>
                              </li>

                                                                                  
                              
                ";
        // line 728
        echo "                                            
                              <li class=\"link-leveltwo\" data-submenu=\"31\" id=\"subtab-AdminReturn\">
                                <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminReturn&amp;token=da61ce6c0d6a91cb2818cfbe92e56c40\" class=\"link\"> Retours produits
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"32\" id=\"subtab-AdminStats\">
                    <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminStats&amp;token=ea9e7bdab007f683520df524abbcc973\" class=\"link\">
                      <i class=\"material-icons mi-assessment\">assessment</i>
                      <span>
                      Statistiques
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"37\" id=\"tab-IMPROVE\">
                <span class=\"title\">Personnaliser</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"38\" id=\"subtab-AdminParentModulesSf\">
                    <a href=\"/admin3295/index.php/modules/";
        // line 766
        echo "mbo/modules/catalog/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Modules
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-38\" class=\"submenu panel-collapse\">
                                                                                                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"162\" id=\"subtab-AdminPsMboModuleParent\">
                                <a href=\"/admin3295/index.php/modules/mbo/modules/catalog/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Marketplace
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"39\" id=\"subtab-AdminModulesSf\">
                                <a href=\"/admin3295/index.php/improve/modules/manage?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Gestionnaire de modules 
                                </a>
                              </li>

                                                                                                                                                                                          </ul>
                          ";
        // line 793
        echo "              </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"43\" id=\"subtab-AdminParentThemes\">
                    <a href=\"/admin3295/index.php/improve/design/themes/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-desktop_mac\">desktop_mac</i>
                      <span>
                      Apparence
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-43\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"169\" id=\"subtab-AdminThemesParent\">
                                <a href=\"/admin3295/index.php/improve/design/themes/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Thème et logo
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"166\" id=\"subtab-AdminPsMboTheme\">
                                <a href=\"/admin3295/index.php/modules/mbo/themes/catalog/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Themes Catalog
                                </a>
                              </li>

              ";
        // line 825
        echo "                                                                    
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"45\" id=\"subtab-AdminParentMailTheme\">
                                <a href=\"/admin3295/index.php/improve/design/mail_theme/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Thème d&#039;e-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"47\" id=\"subtab-AdminCmsContent\">
                                <a href=\"/admin3295/index.php/improve/design/cms-pages/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Pages
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"48\" id=\"subtab-AdminModulesPositions\">
                                <a href=\"/admin3295/index.php/improve/design/modules/positions/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Positions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"49\" id=\"subtab-AdminImages\">
                                <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminImages&amp;token=082aacf6b74ab570e3f2650196dd81b8\" class=\"link\"> I";
        // line 853
        echo "mages
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"118\" id=\"subtab-AdminLinkWidget\">
                                <a href=\"/admin3295/index.php/modules/link-widget/list?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Liste de liens
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"50\" id=\"subtab-AdminParentShipping\">
                    <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminCarriers&amp;token=e433eb670f13f237524b27dc2b2e5fd1\" class=\"link\">
                      <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                      <span>
                      Livraison
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-50\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"51\" id=\"subtab-AdminCarriers\">
                                <a href=\"http://l";
        // line 886
        echo "ocalhost:8088/admin3295/index.php?controller=AdminCarriers&amp;token=e433eb670f13f237524b27dc2b2e5fd1\" class=\"link\"> Transporteurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"52\" id=\"subtab-AdminShipping\">
                                <a href=\"/admin3295/index.php/improve/shipping/preferences/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"53\" id=\"subtab-AdminParentPayment\">
                    <a href=\"/admin3295/index.php/improve/payment/payment_methods?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-payment\">payment</i>
                      <span>
                      Paiement
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-53\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-";
        // line 918
        echo "leveltwo\" data-submenu=\"54\" id=\"subtab-AdminPayment\">
                                <a href=\"/admin3295/index.php/improve/payment/payment_methods?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Moyens de paiement
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"55\" id=\"subtab-AdminPaymentPreferences\">
                                <a href=\"/admin3295/index.php/improve/payment/preferences?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"56\" id=\"subtab-AdminInternational\">
                    <a href=\"/admin3295/index.php/improve/international/localization/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-language\">language</i>
                      <span>
                      International
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-56\" class=\"submenu panel-collapse\">
                                                      
                 ";
        // line 949
        echo "             
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"57\" id=\"subtab-AdminParentLocalization\">
                                <a href=\"/admin3295/index.php/improve/international/localization/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Localisation
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"62\" id=\"subtab-AdminParentCountries\">
                                <a href=\"/admin3295/index.php/improve/international/zones/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Zones géographiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"66\" id=\"subtab-AdminParentTaxes\">
                                <a href=\"/admin3295/index.php/improve/international/taxes/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Taxes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"69\" id=\"subtab-AdminTranslations\">
                                <a href=\"/admin3295/index.php/improve/international/translations/settings?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Traductions
                                </a>
           ";
        // line 978
        echo "                   </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"138\" id=\"subtab-Marketing\">
                    <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminPsxMktgWithGoogleModule&amp;token=e2ea5217bb47a15904badbaeb8312300\" class=\"link\">
                      <i class=\"material-icons mi-campaign\">campaign</i>
                      <span>
                      Marketing
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-138\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"139\" id=\"subtab-AdminPsxMktgWithGoogleModule\">
                                <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminPsxMktgWithGoogleModule&amp;token=e2ea5217bb47a15904badbaeb8312300\" class=\"link\"> Google
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"158\" id=\"subtab-AdminPsfacebookModule\">
                                <a href=\"http://localhost:8088/adm";
        // line 1009
        echo "in3295/index.php?controller=AdminPsfacebookModule&amp;token=0c675b18ebd388c63c4a07e37697fe8e\" class=\"link\"> Facebook &amp; Instagram
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"70\" id=\"tab-CONFIGURE\">
                <span class=\"title\">Configurer</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"154\" id=\"subtab-AdminPsEditionBasicSettingsController\">
                    <a href=\"/admin3295/index.php/modules/pseditionbasic/settings?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Paramètres
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"71\" id=\"subtab-ShopParameters\">
                    <a href=\"/admin3295/index.php/configure/shop/preferences/preferences?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
         ";
        // line 1047
        echo "             <span>
                      Paramètres de la boutique
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-71\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"72\" id=\"subtab-AdminParentPreferences\">
                                <a href=\"/admin3295/index.php/configure/shop/preferences/preferences?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Paramètres généraux
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"75\" id=\"subtab-AdminParentOrderPreferences\">
                                <a href=\"/admin3295/index.php/configure/shop/order-preferences/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"78\" id=\"subtab-AdminPPreferences\">
                                <a href=\"/admin3295/index.php/configure/shop/product-preferences/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Produ";
        // line 1075
        echo "its
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"79\" id=\"subtab-AdminParentCustomerPreferences\">
                                <a href=\"/admin3295/index.php/configure/shop/customer-preferences/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"83\" id=\"subtab-AdminParentStores\">
                                <a href=\"/admin3295/index.php/configure/shop/contacts/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Contact
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"86\" id=\"subtab-AdminParentMeta\">
                                <a href=\"/admin3295/index.php/configure/shop/seo-urls/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Trafic et SEO
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"89\" id=\"subtab-AdminParentSearchConf\">
                                <a href=\"http://localhost:8088/a";
        // line 1107
        echo "dmin3295/index.php?controller=AdminSearchConf&amp;token=3b2cef1166f3fb6882688efd21c88fd2\" class=\"link\"> Rechercher
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"92\" id=\"subtab-AdminAdvancedParameters\">
                    <a href=\"/admin3295/index.php/configure/advanced/system-information/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-settings_applications\">settings_applications</i>
                      <span>
                      Paramètres avancés
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-92\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"93\" id=\"subtab-AdminInformation\">
                                <a href=\"/admin3295/index.php/configure/advanced/system-information/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Informations
                                </a>
                              </li>

                                                                                  
                              
                                                            ";
        // line 1138
        echo "
                              <li class=\"link-leveltwo\" data-submenu=\"94\" id=\"subtab-AdminPerformance\">
                                <a href=\"/admin3295/index.php/configure/advanced/performance/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Performances
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"95\" id=\"subtab-AdminAdminPreferences\">
                                <a href=\"/admin3295/index.php/configure/advanced/administration/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Administration
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"96\" id=\"subtab-AdminEmails\">
                                <a href=\"/admin3295/index.php/configure/advanced/emails/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> E-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"97\" id=\"subtab-AdminImport\">
                                <a href=\"/admin3295/index.php/configure/advanced/import/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Importer
                                </a>
                              </li>

                                                                                  
       ";
        // line 1169
        echo "                       
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"98\" id=\"subtab-AdminParentEmployees\">
                                <a href=\"/admin3295/index.php/configure/advanced/employees/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Équipe
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"102\" id=\"subtab-AdminParentRequestSql\">
                                <a href=\"/admin3295/index.php/configure/advanced/sql-requests/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Base de données
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"105\" id=\"subtab-AdminLogs\">
                                <a href=\"/admin3295/index.php/configure/advanced/logs/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Logs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"106\" id=\"subtab-AdminWebservice\">
                                <a href=\"/admin3295/index.php/configure/advanced/webservice-keys/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Webservice
                                </a>
                              </li";
        // line 1198
        echo ">

                                                                                                                                                                                                                                                    
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"110\" id=\"subtab-AdminFeatureFlag\">
                                <a href=\"/admin3295/index.php/configure/advanced/feature-flags/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Fonctionnalités nouvelles et expérimentales
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"111\" id=\"subtab-AdminParentSecurity\">
                                <a href=\"/admin3295/index.php/configure/advanced/security/?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\"> Sécurité
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"128\" id=\"subtab-AdminKlaviyoPsConfig\">
                    <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminKlaviyoPsConfig&amp;token=cf66cb5aef4dc1aefaed5f486adede3a\" class=\"link\">
                      <i class=\"material-icons mi-trending_up\">trending_up</i>
                      <span>
                      Klaviyo
                      </span>
                                                    ";
        // line 1228
        echo "<i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"130\" id=\"subtab-AdminPsAssistantSettings\">
                    <a href=\"http://localhost:8088/admin3295/index.php?controller=AdminPsAssistantSettings&amp;token=4c0a96d55fc60bdaa3b35d3775a1db0f\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Assistance By PrestaShop
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"150\" id=\"tab-AdminPsdistributionapiclientCommunity\">
                <span class=\"title\">Community</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"151\" id=\"subtab-AdminPsdistributionapiclient\">
                    <a href=\"/admin3295/index.php/modules/ps_distributionapiclient/top-contributors?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"link\">
                      <i class=\"material-icons mi-groups\">groups</i>";
        // line 1264
        echo "
                      <span>
                      Wall of Fame
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                  </ul>
  </div>
  
</nav>


<div class=\"header-toolbar d-print-none\">
    
  <div class=\"container-fluid\">

    
      <nav aria-label=\"Breadcrumb\">
        <ol class=\"breadcrumb\">
          
                      <li class=\"breadcrumb-item active\">
              <a href=\"/admin3295/index.php/modules/pseditionbasic/homepage?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" aria-current=\"page\">Bienvenue</a>
            </li>
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
          <h1 class=\"title\">
            Accueil          </h1>
      

      
        <div class=\"toolbar-icons\">
          <div class=\"wrapper\">
            
                        
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Aide\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"https://help.prestashop-project.org/fr/doc/HOME?version=8.2.6&amp;country=fr\"
                   id=\"product_form_open_help\"
                >
                  Aide
                </a>
                                    </div>
        </div>

      
    </div>
  </div>

  
  
  <div class=\"btn-floating\">
    <button class=\"btn btn-primary collapsed\" data-toggle=\"collapse\" data-target=\".btn-floating-container\" aria-expanded=\"false\">
      <i class=\"material-icons\">add</i>
    </button>
    <div class=\"btn-floating-container collapse\">
      <d";
        // line 1331
        echo "iv class=\"btn-floating-menu\">
        
        
                              <a class=\"btn btn-floating-item btn-help btn-sidebar\" href=\"#\"
               title=\"Aide\"
               data-toggle=\"sidebar\"
               data-target=\"#right-sidebar\"
               data-url=\"https://help.prestashop-project.org/fr/doc/HOME?version=8.2.6&amp;country=fr\"
            >
              Aide
            </a>
                        </div>
    </div>
  </div>
  
</div>

<div id=\"main-div\">
          
      <div class=\"content-div  \">

        

                                                        
        <div id=\"ajax_confirmation\" class=\"alert alert-success\" style=\"display: none;\"></div>
<div id=\"content-message-box\"></div>


  ";
        // line 1359
        $this->displayBlock('content_header', $context, $blocks);
        $this->displayBlock('content', $context, $blocks);
        $this->displayBlock('content_footer', $context, $blocks);
        $this->displayBlock('sidebar_right', $context, $blocks);
        echo "

        

      </div>
    </div>

  <div id=\"non-responsive\" class=\"js-non-responsive\">
  <h1>Oh non !</h1>
  <p class=\"mt-3\">
    La version mobile de cette page n'est pas encore disponible.
  </p>
  <p class=\"mt-2\">
    Cette page n'est pas encore disponible sur mobile, merci de la consulter sur ordinateur.
  </p>
  <p class=\"mt-2\">
    Merci.
  </p>
  <a href=\"/admin3295/index.php/modules/pseditionbasic/homepage?_token=w0tFpy10sByiyMRwSRMJtd0uqFg4jjt35bOula3q4Wg\" class=\"btn btn-primary py-1 mt-3\">
    <i class=\"material-icons rtl-flip\">arrow_back</i>
    Précédent
  </a>
</div>
  <div class=\"mobile-layer\"></div>

      <div id=\"footer\" class=\"bootstrap\">
    
</div>
  

      <div class=\"bootstrap\">
      
    </div>
  
";
        // line 1393
        $this->displayBlock('javascripts', $context, $blocks);
        $this->displayBlock('extra_javascripts', $context, $blocks);
        $this->displayBlock('translate_javascripts', $context, $blocks);
        echo "</body>";
        echo "
</html>";
    }

    // line 119
    public function block_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_extra_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 1359
    public function block_content_header($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_content_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_sidebar_right($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 1393
    public function block_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_extra_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_translate_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "__string_template__ea892a5f687fe8ed91a07be90f82233e";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1571 => 1393,  1550 => 1359,  1539 => 119,  1530 => 1393,  1490 => 1359,  1460 => 1331,  1391 => 1264,  1353 => 1228,  1321 => 1198,  1290 => 1169,  1257 => 1138,  1224 => 1107,  1190 => 1075,  1160 => 1047,  1120 => 1009,  1087 => 978,  1056 => 949,  1023 => 918,  989 => 886,  954 => 853,  924 => 825,  890 => 793,  861 => 766,  821 => 728,  789 => 698,  756 => 667,  720 => 633,  689 => 604,  656 => 573,  622 => 541,  583 => 504,  541 => 464,  510 => 435,  464 => 391,  414 => 343,  364 => 295,  317 => 250,  275 => 210,  259 => 196,  217 => 156,  175 => 119,  149 => 95,  124 => 72,  118 => 69,  90 => 43,  46 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__ea892a5f687fe8ed91a07be90f82233e", "");
    }
}
