<?php

/* AppBundle:Page:add-observations-specimens.html.twig */
class __TwigTemplate_7b9b8f5c263b4065b4562c3f176e17af33a46394bcaf71e79cdbb3bc3abc270f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("AppBundle::nocol-layout.html.twig");

        $this->blocks = array(
            'main_content' => array($this, 'block_main_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "AppBundle::nocol-layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_main_content($context, array $blocks = array())
    {
        // line 3
        echo "    <div class=\"col-lg-10\">
        ";
        // line 4
        echo twig_include($this->env, $context, "AppBundle:Bare:add-observations-specimens.html.twig");
        echo "
       </div>
    ";
    }

    public function getTemplateName()
    {
        return "AppBundle:Page:add-observations-specimens.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  34 => 4,  31 => 3,  28 => 2,);
    }
}