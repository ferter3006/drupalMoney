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

/* modules/custom/moneylink_home/templates/moneylink-home.html.twig */
class __TwigTemplate_5a8f69d516f052d7fb44a69a186566a8 extends Template
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
        // line 11
        yield "<div class=\"moneylink-home\">
\t<div
\t\tclass=\"moneylink-home__content\">

\t\t";
        // line 16
        yield "\t\t<div class=\"hero-section\" style=\"text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; margin-bottom: 30px;\">
\t\t\t<h1 style=\"font-size: 2.5em; margin-bottom: 16px; font-weight: bold;\">
\t\t\t\t💰 MoneyLink
\t\t\t</h1>
\t\t\t<p style=\"font-size: 1.2em; margin-bottom: 20px;\">
\t\t\t\tTu aplicación de gestión financiera personal
\t\t\t</p>

\t\t\t";
        // line 24
        if ((($tmp = ($context["is_logged_in"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 25
            yield "\t\t\t\t<p style=\"font-size: 1em; opacity: 0.9;\">
\t\t\t\t\t¡Bienvenido de nuevo,
\t\t\t\t\t<strong>";
            // line 27
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["user_data"] ?? null), "name", [], "any", false, false, true, 27), "html", null, true);
            yield "!</strong>
\t\t\t\t</p>
\t\t\t";
        } else {
            // line 30
            yield "\t\t\t\t<p style=\"font-size: 1em; opacity: 0.9;\">
\t\t\t\t\tComienza a gestionar tus finanzas de manera inteligente
\t\t\t\t</p>
\t\t\t";
        }
        // line 34
        yield "\t\t</div>

\t\t";
        // line 37
        yield "\t\t<div class=\"features-section\" style=\"display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;\">

\t\t\t<div class=\"feature-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa;\">
\t\t\t\t<h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
\t\t\t\t\t🏠
\t\t\t\t\t<span style=\"margin-left: 8px;\">Gestión de Salas</span>
\t\t\t\t</h3>
\t\t\t\t<p style=\"color: #4a5568; line-height: 1.6;\">
\t\t\t\t\tOrganiza tus finanzas en diferentes \"salas\" o categorías. Cada sala puede representar un aspecto diferente de tu vida financiera: gastos del hogar, inversiones, ahorros, etc.
\t\t\t\t</p>
\t\t\t</div>

\t\t\t<div class=\"feature-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa;\">
\t\t\t\t<h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
\t\t\t\t\t📊
\t\t\t\t\t<span style=\"margin-left: 8px;\">Control de Ingresos y Gastos</span>
\t\t\t\t</h3>
\t\t\t\t<p style=\"color: #4a5568; line-height: 1.6;\">
\t\t\t\t\tRegistra todos tus movimientos financieros con tiquets detallados. Visualiza fácilmente tus ingresos en verde y gastos en rojo para mantener un balance claro.
\t\t\t\t</p>
\t\t\t</div>

\t\t\t<div class=\"feature-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa;\">
\t\t\t\t<h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
\t\t\t\t\t📅
\t\t\t\t\t<span style=\"margin-left: 8px;\">Seguimiento Mensual</span>
\t\t\t\t</h3>
\t\t\t\t<p style=\"color: #4a5568; line-height: 1.6;\">
\t\t\t\t\tNavega fácilmente entre meses para ver tu evolución financiera a lo largo del tiempo. Cada sala mantiene su historial completo mes a mes.
\t\t\t\t</p>
\t\t\t</div>

\t\t\t<div class=\"feature-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa;\">
\t\t\t\t<h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
\t\t\t\t\t👤
\t\t\t\t\t<span style=\"margin-left: 8px;\">Perfil Personalizado</span>
\t\t\t\t</h3>
\t\t\t\t<p style=\"color: #4a5568; line-height: 1.6;\">
\t\t\t\t\tGestiona tu información personal y configura tu experiencia. Mantén tu perfil actualizado para un mejor control de tus datos.
\t\t\t\t</p>
\t\t\t</div>
\t\t</div>

\t\t";
        // line 81
        yield "\t\t";
        if ((($tmp = ($context["is_logged_in"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 82
            yield "\t\t\t<div class=\"cta-section\" style=\"text-align: center; padding: 30px; background: #f7fafc; border-radius: 8px; border-left: 4px solid #48bb78;\">
\t\t\t\t<h3 style=\"color: #2d3748; margin-bottom: 16px;\">¿Listo para continuar?</h3>
\t\t\t\t<p style=\"color: #4a5568; margin-bottom: 20px;\">
\t\t\t\t\tAccede a tu panel de usuario para gestionar tus salas y finanzas.
\t\t\t\t</p>
\t\t\t\t<a href=\"/ml/userpanel\" class=\"button button--primary\" style=\"display: inline-block; padding: 12px 24px; background: #4299e1; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;\">
\t\t\t\t\tIr a Mi Panel
\t\t\t\t</a>
\t\t\t</div>
\t\t";
        } else {
            // line 92
            yield "\t\t\t<div class=\"cta-section\" style=\"text-align: center; padding: 30px; background: #f7fafc; border-radius: 8px; border-left: 4px solid #4299e1;\">
\t\t\t\t<h3 style=\"color: #2d3748; margin-bottom: 16px;\">¿Listo para empezar?</h3>
\t\t\t\t<p style=\"color: #4a5568; margin-bottom: 20px;\">
\t\t\t\t\tInicia sesión para comenzar a gestionar tus finanzas de manera inteligente.
\t\t\t\t</p>
\t\t\t\t<a href=\"/ml/login\" class=\"button button--primary\" style=\"display: inline-block; padding: 12px 24px; background: #4299e1; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;\">
\t\t\t\t\tIniciar Sesión
\t\t\t\t</a>
\t\t\t</div>
\t\t";
        }
        // line 102
        yield "
\t</div>
</div>


<style>
\t.moneylink-home {
\t\twidth: 100%;
\t\tmax-width: 800px;
\t\tmargin: 0 auto;
\t\tpadding: 20px;
\t}

</style>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["is_logged_in", "user_data"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/moneylink_home/templates/moneylink-home.html.twig";
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
        return array (  154 => 102,  142 => 92,  130 => 82,  127 => 81,  82 => 37,  78 => 34,  72 => 30,  66 => 27,  62 => 25,  60 => 24,  50 => 16,  44 => 11,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/moneylink_home/templates/moneylink-home.html.twig", "/var/www/html/web/modules/custom/moneylink_home/templates/moneylink-home.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 24];
        static $filters = ["escape" => 27];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape'],
                [],
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
