    {# config.volt #}
    Ext.Loader.setConfig({
        enabled: true,
        paths: {
          'Cms': '/extjs/lib/cms',
          'Ext': '/extjs/lib/src',
          'Ext.ux': '/extjs/lib/ux',
          'Ext.ux.app':'/extjs/lib/ux/app',
          'ExtjsCms': '/extjs/apps/ExtjsCms',
{% for module in modules %}
           {{ module }}: '/extjs/apps/{{ module }}',
{% endfor %}
        }
    });