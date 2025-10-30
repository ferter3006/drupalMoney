<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* modules/custom/moneylink_salas/templates/moneylink-showsala.html.twig */
class __TwigTemplate_09484ba5b4bd7b521dce98b4850ea4d9 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 14
        yield "<div class=\"wrapper-class\">

  ";
        // line 17
        yield "  <div class=\"nav-back\">
    <a href=\"";
        // line 18
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("moneylink_salas.content"));
        yield "\" class=\"button button--secondary\" title=\"Volver al panel de salas\">
      ← Back
    </a>
  </div>

  ";
        // line 24
        yield "  <div class=\"nav-month\" style=\"display:flex; justify-content:space-between; align-items:center;\">
    ";
        // line 26
        yield "    <a href=\"";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("moneylink_salas.show", ["id" => ($context["id"] ?? null), "mes" => (($context["mes"] ?? null) - 1)]), "html", null, true);
        yield "\" class=\"button button--primary\" title=\"Mes anterior\">
      ←
    </a>

    ";
        // line 31
        yield "    <span class=\"sala-date\">    
      <strong>
        ";
        // line 33
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["sala_data"] ?? null), "mes", [], "any", false, false, true, 33), "html", null, true);
        yield "
        -
        ";
        // line 35
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["sala_data"] ?? null), "año", [], "any", false, false, true, 35), "html", null, true);
        yield "        
      </strong>
    </span>

    ";
        // line 40
        yield "    <a href=\"";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("moneylink_salas.show", ["id" => ($context["id"] ?? null), "mes" => (($context["mes"] ?? null) + 1)]), "html", null, true);
        yield "\" class=\"button button--primary\" title=\"Mes siguiente\">
      →
    </a>
  </div>

  ";
        // line 46
        yield "  <div class=\"sala-balance\" style=\"margin:12px 0; padding:12px; border:1px solid #ccc; border-radius:6px; background-color:#f9f9f9;\">
    <h3>
      Balance del mes
    </h3>
    <div style=\"font-size:1.5em; font-weight:bold;
      color: ";
        // line 51
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["sala_data"] ?? null), "sala", [], "any", false, false, true, 51), "balance", [], "any", false, false, true, 51) >= 0)) ? ("green") : ("red")));
        yield ";\">
      ";
        // line 52
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["sala_data"] ?? null), "sala", [], "any", false, false, true, 52), "balance", [], "any", false, false, true, 52) >= 0)) ? ("+") : ("")));
        yield "
      ";
        // line 53
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["sala_data"] ?? null), "sala", [], "any", false, false, true, 53), "balance", [], "any", false, false, true, 53), 2, ".", ","), "html", null, true);
        yield "
    </div>
  </div>
  ";
        // line 57
        yield "  <div class=\"sala-tiquets\">
    <h3>
      Tiquets
    </h3>

    ";
        // line 62
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["sala_data"] ?? null), "sala", [], "any", false, false, true, 62), "tiquets", [], "any", false, false, true, 62));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["tiquet"]) {
            // line 63
            yield "      <div class=\"card\" style=\"border:1px solid #ccc; border-radius:6px; padding:12px; margin-bottom:12px;\">

        ";
            // line 66
            yield "        <div>
          <strong>
            Tiquet #
            ";
            // line 69
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["tiquet"], "id", [], "any", false, false, true, 69), "html", null, true);
            yield "
          </strong>
          -
          ";
            // line 72
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["tiquet"], "user_name", [], "any", false, false, true, 72), "html", null, true);
            yield "
        </div>

        ";
            // line 76
            yield "        <div>
          <em>
            ";
            // line 78
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["tiquet"], "category_name", [], "any", false, false, true, 78), "html", null, true);
            yield "
          </em>
        </div>

        ";
            // line 83
            yield "        <div style=\"font-size:0.9em; color:#555; margin:6px 0;\">
          ";
            // line 84
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["tiquet"], "description", [], "any", false, false, true, 84), "html", null, true);
            yield "
        </div>

        ";
            // line 88
            yield "        <div style=\"font-weight:bold; font-size:1.1em;
          color: ";
            // line 89
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["tiquet"], "es_ingreso", [], "any", false, false, true, 89)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("green") : ("red")));
            yield ";\">
          ";
            // line 90
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["tiquet"], "es_ingreso", [], "any", false, false, true, 90)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("+") : ("-")));
            yield "
          ";
            // line 91
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["tiquet"], "amount", [], "any", false, false, true, 91), 2, ".", ","), "html", null, true);
            yield "
        </div>
      </div>
    ";
            $context['_iterated'] = true;
        }
        // line 94
        if (!$context['_iterated']) {
            // line 95
            yield "      <p>
        No hay tiquets disponibles.
      </p>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['tiquet'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 99
        yield "  </div>

</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["id", "mes", "sala_data"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/moneylink_salas/templates/moneylink-showsala.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  199 => 99,  190 => 95,  188 => 94,  180 => 91,  176 => 90,  172 => 89,  169 => 88,  163 => 84,  160 => 83,  153 => 78,  149 => 76,  143 => 72,  137 => 69,  132 => 66,  128 => 63,  123 => 62,  116 => 57,  110 => 53,  106 => 52,  102 => 51,  95 => 46,  86 => 40,  79 => 35,  74 => 33,  70 => 31,  62 => 26,  59 => 24,  51 => 18,  48 => 17,  44 => 14,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/moneylink_salas/templates/moneylink-showsala.html.twig", "/var/www/html/web/modules/custom/moneylink_salas/templates/moneylink-showsala.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 62];
        static $filters = ["escape" => 26, "number_format" => 53];
        static $functions = ["url" => 18];

        try {
            $this->sandbox->checkSecurity(
                ['for'],
                ['escape', 'number_format'],
                ['url'],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
