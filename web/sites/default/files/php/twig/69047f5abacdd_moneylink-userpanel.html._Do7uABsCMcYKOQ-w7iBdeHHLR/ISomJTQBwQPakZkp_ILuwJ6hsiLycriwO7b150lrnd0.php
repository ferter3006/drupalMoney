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

/* modules/custom/moneylink_userpanel/templates/moneylink-userpanel.html.twig */
class __TwigTemplate_bd892e1562db8a37ba28b697daa7e049 extends Template
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
        // line 10
        yield "<div class=\"moneylink-userpanel\">
  <div class=\"moneylink-userpanel__content\">
    
    ";
        // line 14
        yield "    <div class=\"hero-section\" style=\"text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; margin-bottom: 30px;\">
      <h1 style=\"font-size: 2.5em; margin-bottom: 16px; font-weight: bold;\">
        👋 ¡Bienvenido de nuevo!
      </h1>
      <p style=\"font-size: 1.4em; margin-bottom: 12px;\">
        <strong>";
        // line 19
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((CoreExtension::getAttribute($this->env, $this->source, ($context["user_data"] ?? null), "name", [], "any", true, true, true, 19)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["user_data"] ?? null), "name", [], "any", false, false, true, 19), "Usuario")) : ("Usuario")), "html", null, true);
        yield "</strong>
      </p>
      <p style=\"font-size: 1em; opacity: 0.9;\">
        Tu panel de control financiero personal
      </p>
    </div>

    ";
        // line 27
        yield "    <div class=\"actions-section\" style=\"display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;\">
      
      ";
        // line 30
        yield "      <div class=\"action-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa; transition: transform 0.2s, box-shadow 0.2s;\">
        <h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
          🏠 <span style=\"margin-left: 8px;\">Visitar Tus Salas</span>
        </h3>
        <p style=\"color: #4a5568; line-height: 1.6; margin-bottom: 16px;\">
          Accede a todas tus salas financieras. Revisa ingresos, gastos y mantén tu control financiero al día.
        </p>
        <a href=\"/ml/salas\" class=\"button button--primary\" style=\"display: inline-block; padding: 10px 20px; background: #48bb78; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background 0.2s;\">
          Ver Mis Salas
        </a>
      </div>

      ";
        // line 43
        yield "      <div class=\"action-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa; transition: transform 0.2s, box-shadow 0.2s;\">
        <h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
          👥 <span style=\"margin-left: 8px;\">Invitar Amigos</span>
        </h3>
        <p style=\"color: #4a5568; line-height: 1.6; margin-bottom: 16px;\">
          Invita a familiares y amigos a unirse a tus salas para gestionar finanzas compartidas como gastos del hogar.
        </p>
        <button class=\"button button--secondary\" style=\"display: inline-block; padding: 10px 20px; background: #4299e1; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; border: none; cursor: pointer; transition: background 0.2s;\" onclick=\"alert('Funcionalidad próximamente disponible')\">
          Invitar Amigos
        </button>
      </div>

      ";
        // line 56
        yield "      <div class=\"action-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa; transition: transform 0.2s, box-shadow 0.2s;\">
        <h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
          📊 <span style=\"margin-left: 8px;\">Ver Gráficos</span>
        </h3>
        <p style=\"color: #4a5568; line-height: 1.6; margin-bottom: 16px;\">
          Visualiza tus datos financieros con gráficos detallados. Analiza tendencias y patrones de gasto mes a mes.
        </p>
        <button class=\"button button--secondary\" style=\"display: inline-block; padding: 10px 20px; background: #ed8936; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; border: none; cursor: pointer; transition: background 0.2s;\" onclick=\"alert('Funcionalidad próximamente disponible')\">
          Ver Estadísticas
        </button>
      </div>

      ";
        // line 69
        yield "      <div class=\"action-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa; transition: transform 0.2s, box-shadow 0.2s;\">
        <h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
          ⚙️ <span style=\"margin-left: 8px;\">Mi Perfil</span>
        </h3>
        <p style=\"color: #4a5568; line-height: 1.6; margin-bottom: 16px;\">
          Actualiza tu información personal, cambia configuraciones y gestiona tu cuenta de MoneyLink.
        </p>
        <a href=\"/ml/perfil\" class=\"button button--primary\" style=\"display: inline-block; padding: 10px 20px; background: #805ad5; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background 0.2s;\">
          Ver Perfil
        </a>
      </div>

      ";
        // line 82
        yield "      <div class=\"action-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa; transition: transform 0.2s, box-shadow 0.2s;\">
        <h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
          📋 <span style=\"margin-left: 8px;\">Reportes Mensuales</span>
        </h3>
        <p style=\"color: #4a5568; line-height: 1.6; margin-bottom: 16px;\">
          Genera reportes detallados de tus movimientos financieros y exporta datos para análisis externos.
        </p>
        <button class=\"button button--secondary\" style=\"display: inline-block; padding: 10px 20px; background: #38b2ac; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; border: none; cursor: pointer; transition: background 0.2s;\" onclick=\"alert('Funcionalidad próximamente disponible')\">
          Generar Reporte
        </button>
      </div>

      ";
        // line 95
        yield "      <div class=\"action-card\" style=\"padding: 24px; border: 1px solid #e1e5e9; border-radius: 8px; background: #f8f9fa; transition: transform 0.2s, box-shadow 0.2s;\">
        <h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
          🎯 <span style=\"margin-left: 8px;\">Metas de Ahorro</span>
        </h3>
        <p style=\"color: #4a5568; line-height: 1.6; margin-bottom: 16px;\">
          Establece y rastrea tus objetivos financieros. Define metas de ahorro y monitorea tu progreso.
        </p>
        <button class=\"button button--secondary\" style=\"display: inline-block; padding: 10px 20px; background: #e53e3e; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; border: none; cursor: pointer; transition: background 0.2s;\" onclick=\"alert('Funcionalidad próximamente disponible')\">
          Configurar Metas
        </button>
      </div>
    </div>

    ";
        // line 109
        yield "    <div class=\"summary-section\" style=\"display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 30px;\">
      
      <div class=\"summary-card\" style=\"padding: 20px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 8px; text-align: center;\">
        <h4 style=\"margin-bottom: 8px; font-size: 0.9em; opacity: 0.9;\">Total de Salas</h4>
        <p style=\"font-size: 2em; font-weight: bold; margin: 0;\">-</p>
        <small style=\"opacity: 0.8;\">Próximamente</small>
      </div>

      <div class=\"summary-card\" style=\"padding: 20px; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 8px; text-align: center;\">
        <h4 style=\"margin-bottom: 8px; font-size: 0.9em; opacity: 0.9;\">Balance General</h4>
        <p style=\"font-size: 2em; font-weight: bold; margin: 0;\">-</p>
        <small style=\"opacity: 0.8;\">Próximamente</small>
      </div>

      <div class=\"summary-card\" style=\"padding: 20px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 8px; text-align: center;\">
        <h4 style=\"margin-bottom: 8px; font-size: 0.9em; opacity: 0.9;\">Último Movimiento</h4>
        <p style=\"font-size: 2em; font-weight: bold; margin: 0;\">-</p>
        <small style=\"opacity: 0.8;\">Próximamente</small>
      </div>
    </div>

    ";
        // line 131
        yield "    <div class=\"tips-section\" style=\"background: #f7fafc; border-radius: 8px; border-left: 4px solid #4299e1; padding: 20px; margin-bottom: 20px;\">
      <h3 style=\"color: #2d3748; margin-bottom: 12px; display: flex; align-items: center;\">
        💡 <span style=\"margin-left: 8px;\">Consejo del día</span>
      </h3>
      <p style=\"color: #4a5568; line-height: 1.6; margin: 0;\">
        <strong>¿Sabías que...?</strong> Revisar tus gastos semanalmente te ayuda a mantener mejor control sobre tu presupuesto. ¡Dedica 10 minutos cada domingo a revisar tus movimientos!
      </p>
    </div>

  </div>
</div>

<style>
/* Efectos hover para las tarjetas */
.action-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.button:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}

/* Responsivo */
@media (max-width: 768px) {
  .actions-section {
    grid-template-columns: 1fr;
  }
  
  .summary-section {
    grid-template-columns: 1fr;
  }
  
  .hero-section h1 {
    font-size: 2em !important;
  }
}
</style>";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["user_data"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/custom/moneylink_userpanel/templates/moneylink-userpanel.html.twig";
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
        return array (  178 => 131,  155 => 109,  140 => 95,  126 => 82,  112 => 69,  98 => 56,  84 => 43,  70 => 30,  66 => 27,  56 => 19,  49 => 14,  44 => 10,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/custom/moneylink_userpanel/templates/moneylink-userpanel.html.twig", "/var/www/html/web/modules/custom/moneylink_userpanel/templates/moneylink-userpanel.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = [];
        static $filters = ["escape" => 19, "default" => 19];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape', 'default'],
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
