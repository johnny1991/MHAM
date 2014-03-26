<?php

/* ManagerHABundle:Default:index.html.twig */
class __TwigTemplate_2d8a380fdb36431538ba88644b713820331711361093a9d69e318c5e93bfce90 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
    <head>
    ";
        // line 3
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 7
        echo "        <script src=\"http://code.jquery.com/jquery-1.11.0.min.js\"></script>
    </head>
    <body>
        <div class=\"container\">
            <div class=\"row header\">
                <div class=\"col-sm-12\">
                    <h1>Gestionnaire de Base de données</h1>
                </div>
            </div>
            <div class=\"row main\">
                <div class=\"col-sm-12\">
                    <div id=\"bdd_state\">
                        <h2>Etat des Bases de données</h2>
                        <div class=\"row head\">
                            <div class=\"col-sm-2\">IP</div>
                            <div class=\"col-sm-2\">Status</div>
                            <div class=\"col-sm-2\">Log Binaire</div>
                            <div class=\"col-sm-2\">Position Binaire</div>
                            <div class=\"col-sm-2\">IO Running</div>
                            <div class=\"col-sm-2\">SQL Running</div>
                        </div>
                        ";
        // line 28
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["bdd"]) ? $context["bdd"] : $this->getContext($context, "bdd")));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 29
            echo "                            <div class=\"row\">
                                <div class=\"col-sm-2\">";
            // line 30
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["item"]) ? $context["item"] : $this->getContext($context, "item")), "ip"), "html", null, true);
            echo "</div>
                                <div class=\"col-sm-2\">";
            // line 31
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["item"]) ? $context["item"] : $this->getContext($context, "item")), "status"), "html", null, true);
            echo "</div>
                                <div class=\"col-sm-2\">";
            // line 32
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["item"]) ? $context["item"] : $this->getContext($context, "item")), "log_binaire"), "html", null, true);
            echo "</div>
                                <div class=\"col-sm-2\">";
            // line 33
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["item"]) ? $context["item"] : $this->getContext($context, "item")), "pos_binaire"), "html", null, true);
            echo "</div>
                                <div class=\"col-sm-2\">";
            // line 34
            echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["item"]) ? $context["item"] : null), "io_running", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["item"]) ? $context["item"] : null), "io_running"), "&nbsp;")) : ("&nbsp;")), "html", null, true);
            echo "</div>
                                <div class=\"col-sm-2\">";
            // line 35
            echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["item"]) ? $context["item"] : null), "sql_running", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["item"]) ? $context["item"] : null), "sql_running"), "&nbsp;")) : ("&nbsp;")), "html", null, true);
            echo "</div>
                            </div>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 38
        echo "                    </div>
                    <div id=\"mha_state\">
                        <h2>Etat du Manager MHA</h2>
                        ";
        // line 41
        if (true) {
            // line 42
            echo "                            <span class=\"glyphicon glyphicon-ok\"></span>
                            <span>Le manager MHA est en cours d'éxecution</span>
                        ";
        } else {
            // line 45
            echo "                            <span class=\"glyphicon glyphicon-remove\"></span>
                            <span>Le manager MHA n'est pas en cours d'éxecution</span>
                        ";
        }
        // line 48
        echo "                        <div class=\"log\">
                            En cours de chargement ...
                        </div>
                        <div class=\"time\">Date du Log : <span id=\"refresh_time\"></span></div>
                        <script>
                            setInterval(function (){
                                \$('#mha_state .log').load(\"";
        // line 54
        echo $this->env->getExtension('routing')->getPath("manager_ha_log");
        echo "\").fadeIn(\"slow\");
                                \$('#mha_state #refresh_time').html(new Date(\$.now()));
                            }, 500);
                        </script>
                    </div>
                </div>
            </div>
            <div class=\"row footer\">
                <div class=\"col-sm-12\">
                    Developped by Johnny Cottereau
                </div>
            </div>
        </div>
    </body>
</html>";
    }

    // line 3
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 4
        echo "        <link rel=\"stylesheet\" href=\"//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/manager/css/style.css"), "html", null, true);
        echo "\">
    ";
    }

    public function getTemplateName()
    {
        return "ManagerHABundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 5,  132 => 4,  129 => 3,  110 => 54,  102 => 48,  97 => 45,  92 => 42,  90 => 41,  85 => 38,  76 => 35,  72 => 34,  68 => 33,  64 => 32,  60 => 31,  56 => 30,  53 => 29,  49 => 28,  26 => 7,  24 => 3,  20 => 1,);
    }
}
