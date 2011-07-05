CKEDITOR.plugins.add('kr_media',
{
    init: function(editor)
    {
        var pluginName = 'kr_media';
        CKEDITOR.dialog.add(pluginName, this.path + 'dialogs/media.js');
        editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName));
        editor.ui.addButton('Media',
            {
                label: 'Krustr Media',
                command: pluginName
            });
    }
});