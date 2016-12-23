<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}{% endblock %}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {%- block head -%}

    {%- endblock -%}

    {{ assets.outputJs() }}
    {{ assets.outputCss() }}
</head>
<script type="text/javascript">

    var ADMIN_PREFIX = '{{ ADMIN_PREFIX }}';

    Ext.Loader.setConfig({enabled: true});

    {% include "layouts/config.volt" %}
    {% include "layouts/require.volt" %}

    {%- block content -%}
    {%- endblock -%}


    Ext.onReady(function() {
        // developmenrt

        Ext.log = function () {
            console.log.apply(console, arguments);
        }

        Ext.error = function () {
            console.error.apply(console, arguments);
        }

        /**
         * wrapper around console.warn for translations this can be turned on to show what
         * text is not yet translated
         */
        Ext.translationLog = function () {
//    console.warn.apply(console, arguments);
        }

        // environment
        Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
            expires:new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 7)) //7 days from now
        }));

        Ext.tip.QuickTipManager.init();

        Ext.application('Cms.app.Application');

    });
</script>
<body class="loading-bg">
<div id="logo"><img src="/images/logo.png"></div>
<div id="loading">
    <div class="loading-indicator">
        <img src="/extjs/lib/cms/images/loader.gif" width="32" height="32"
             style="margin-right:8px;float:left;vertical-align:top;"/>
        {{ app_title }} - <a href="http://{{ host }}" target="_blank">{{ host_title }}</a>
        <br/><span id="loading-msg">Loading application...</span>
    </div>
</div>
</body>
</html>