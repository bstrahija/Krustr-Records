<div id="fileupload-<?php echo $field_id; ?>" class="fileuploader">
    <form action="<?php echo admin_url('content/upload_image/'.$entry_id.'/'.$field_id); ?>" method="POST" enctype="multipart/form-data">
        <div class="fileupload-buttonbar">
            <label class="fileinput-button">
                <span>Add files...</span>
                <input type="file" name="files[]" multiple>
            </label>
            <input type="submit" value="Start upload" class="start">
            <input type="reset" value="Cancel upload" class="cancel">
            <input type="button" value="Delete files" class="delete">
        </div>
    </form>
    <div class="fileupload-content">
        <table class="files"></table>
        <div class="fileupload-progressbar"></div>
    </div>
</div>
