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

/* @Modules/ps_mbo/views/templates/hook/twig/module_manager_additional_description.html.twig */
class __TwigTemplate_9810bc892b209f0573f8ec6fbf592d42 extends Template
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
        if (( !twig_test_empty(($context["moduleUrl"] ?? null)) &&  !twig_test_empty(($context["moduleName"] ?? null)))) {
            // line 20
            echo "  <span class=\"mbo-module-read-more-grid\">
          <a class=\"mbo-module-read-more-list-btn url\" id=\"see-more-";
            // line 21
            echo twig_escape_filter($this->env, ($context["moduleName"] ?? null), "html", null, true);
            echo "\" href=\"";
            echo twig_escape_filter($this->env, ($context["moduleUrl"] ?? null), "html", null, true);
            echo "\" target=\"_blank\">
            ";
            // line 22
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Read More", [], "Modules.Mbo.Modulescatalog"), "html", null, true);
            echo "
          </a>
  </span>
";
        }
    }

    public function getTemplateName()
    {
        return "@Modules/ps_mbo/views/templates/hook/twig/module_manager_additional_description.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 22,  42 => 21,  39 => 20,  37 => 19,);
    }

    public function getSourceContext()
    {
        return new Source("", "@Modules/ps_mbo/views/templates/hook/twig/module_manager_additional_description.html.twig", "/var/www/html/modules/ps_mbo/views/templates/hook/twig/module_manager_additional_description.html.twig");
    }
}
