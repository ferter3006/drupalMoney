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

/* modules/custom/moneylink_salas/templates/moneylink-salas.html.twig */
class __TwigTemplate_26101ed71fa4fadd0224fb991eb1a10e extends Template
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
        // line 1
        yield "<div class=\"moneylink-salas\">
  <h2>
    ";
        // line 3
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["message"] ?? null), "html", null, true);
        yield "
  </h2>

  <div class=\"salas-user-info\">
    <div class=\"user-field\">

      ";
        // line 9
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["salas"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["sala"]) {
            // line 10
            yield "        <a href=\"";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("moneylink_salas.show", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["sala"], "id", [], "any", false, false, true, 10), "mes" => 0]), "html", null, true);
            yield "\" class=\"sala-card\" style=\"display:block; border:1px solid #ccc; border-radius:6px; padding:12px; margin-bottom:12px; text-decoration:none; color:inherit;\">

          <h3>
            Sala [
            ";
            // line 14
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["sala"], "id", [], "any", false, false, true, 14), "html", null, true);
            yield "
            ]:
            ";
            // line 16
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["sala"], "name", [], "any", false, false, true, 16), "html", null, true);
            yield "
          </h3>

        ";
            // line 20
            yield "
        </a>
      ";
            $context['_iterated'] = true;
        }
        // line 22
        if (!$context['_iterated']) {
            // line 23
            yield "        <p>
          No hay salas disponibles.
        </p>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['sala'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        yield "
      <div style=\"margin-top:20px;\">
        <a href=\"";
        // line 29
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("moneylink_salas.create"));
        yield "\" class=\"button\">
          Crear nueva sala
        </a>
      </div>

    </div>
  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["message", "salas"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/moneylink_salas/templates/moneylink-salas.html.twig";
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
        return array (  102 => 29,  98 => 27,  89 => 23,  87 => 22,  81 => 20,  75 => 16,  70 => 14,  62 => 10,  57 => 9,  48 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/moneylink_salas/templates/moneylink-salas.html.twig", "/var/www/html/web/modules/custom/moneylink_salas/templates/moneylink-salas.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 9];
        static $filters = ["escape" => 3];
        static $functions = ["url" => 10];

        try {
            $this->sandbox->checkSecurity(
                ['for'],
                ['escape'],
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
