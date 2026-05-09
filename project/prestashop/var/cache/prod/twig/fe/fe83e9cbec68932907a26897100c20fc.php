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

/* @Modules/ps_mbo/views/templates/admin/controllers/module_catalog/cdc-error.html.twig */
class __TwigTemplate_c3d1c9574e20ed1d06462393f662c9a5 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 19
        echo "<div class=\"cdc-error-container\">
  <div class=\"cdc-error-content\">
    <div class=\"cdc-error-content-wrapper\">
      <h1 class=\"cdc-error-title\">
        ";
        // line 23
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Oh no.. something went wrong", [], "Modules.Mbo.Global"), "html", null, true);
        echo "
      </h1>
      <p class=\"cdc-error-message\">
        ";
        // line 26
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("There has been a problem on our side. Refresh your page and if the problem persists, try again later.", [], "Modules.Mbo.Global"), "html", null, true);
        echo "
      </p>
    </div>
    <div class=\"cdc-error-button-container\">
      <button class=\"cdc-error-button\" onclick=\"javascript:window.location.reload();\">
        ";
        // line 31
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Refresh page", [], "Modules.Mbo.Global"), "html", null, true);
        echo "
      </button>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "@Modules/ps_mbo/views/templates/admin/controllers/module_catalog/cdc-error.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 31,  49 => 26,  43 => 23,  37 => 19,);
    }

    public function getSourceContext()
    {
        return new Source("", "@Modules/ps_mbo/views/templates/admin/controllers/module_catalog/cdc-error.html.twig", "/var/www/html/modules/ps_mbo/views/templates/admin/controllers/module_catalog/cdc-error.html.twig");
    }
}
