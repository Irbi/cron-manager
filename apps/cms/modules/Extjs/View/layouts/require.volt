{# require.volt #}
    Ext.require([
        'Ext.grid.*',
        'Ext.data.*',
        'Ext.util.*',
        'Ext.toolbar.Paging',
        'Ext.ux.PreviewPlugin',
        'Ext.ux.TabScrollerMenu',
        'Ext.ModelManager',

{% for module in modules %}
        '{{ module }}.controller.*',
        '{{ module }}.store.*',
        '{{ module }}.model.*',
        '{{ module }}.view.*',
        '{{ module }}.view.*.*',
{% endfor %}
    ]);
