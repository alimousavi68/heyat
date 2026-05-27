jQuery(document).ready(function($){
    
    // 1. Single Image Uploader (Slider)
    $('.heyat-upload-btn').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var targetInput = $(button.data('target'));
        
        var uploader = wp.media({
            title: 'انتخاب تصویر',
            button: { text: 'استفاده از این تصویر' },
            multiple: false
        }).on('select', function() {
            var attachment = uploader.state().get('selection').first().toJSON();
            targetInput.val(attachment.url);
        }).open();
    });

    // 2. Media Repeater Row Uploader
    $('body').on('click', '.heyat-repeater-upload', function(e) {
        e.preventDefault();
        var button = $(this);
        var targetInput = button.siblings('.media-url-input');
        
        var uploader = wp.media({
            title: 'انتخاب فایل رسانه',
            button: { text: 'انتخاب فایل' },
            multiple: false
        }).on('select', function() {
            var attachment = uploader.state().get('selection').first().toJSON();
            targetInput.val(attachment.url);
        }).open();
    });

    // 3. Add Repeater Row
    $('#heyat-add-media-row').click(function(e) {
        e.preventDefault();
        var rowHtml = `
            <div class="heyat-repeater-row">
                <input type="text" name="heyat_media_quality[]" value="" placeholder="کیفیت (مثال: 128kbps یا 1080p)" />
                <input type="text" name="heyat_media_duration[]" value="" placeholder="مدت زمان (مثال: 12:45)" />
                <input type="text" name="heyat_media_url[]" class="media-url-input" value="" placeholder="آدرس فایل" />
                <button type="button" class="button heyat-repeater-upload">انتخاب فایل</button>
                <button type="button" class="button button-danger heyat-repeater-remove" style="color:red; border-color:red;">حذف</button>
            </div>
        `;
        $('#heyat-media-repeater').append(rowHtml);
    });

    // 4. Remove Repeater Row
    $('body').on('click', '.heyat-repeater-remove', function(e) {
        e.preventDefault();
        if(confirm('آیا از حذف این ردیف اطمینان دارید؟')) {
            $(this).parent('.heyat-repeater-row').remove();
        }
    });

    // 5. Gallery Multi-Uploader
    $('#heyat_gallery_upload_btn').click(function(e) {
        e.preventDefault();
        
        var uploader = wp.media({
            title: 'انتخاب تصاویر گالری',
            button: { text: 'افزودن به گالری' },
            multiple: true
        }).on('select', function() {
            var selection = uploader.state().get('selection');
            var attachmentIds = [];
            var previewHtml = '';
            
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                attachmentIds.push(attachment.id);
                // Use thumbnail if available, else full url
                var imgUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                previewHtml += '<img src="' + imgUrl + '" alt="" />';
            });
            
            // Append to existing or replace? Let's replace for simplicity
            $('#heyat_gallery_images_input').val(attachmentIds.join(','));
            $('#heyat_gallery_preview').html(previewHtml);
            
        }).open();
    });

    // 6. Clear Gallery
    $('#heyat_gallery_clear_btn').click(function(e) {
        e.preventDefault();
        if(confirm('آیا از پاک کردن کل گالری اطمینان دارید؟')) {
            $('#heyat_gallery_images_input').val('');
            $('#heyat_gallery_preview').html('');
        }
    });

    // 7. Select2 and Persian Datepicker for Events
    if ($('.heyat-select2-person').length > 0) {
        function formatPerson(person) {
            if (!person.id) {
                return person.text;
            }
            var $img = $(person.element).data('image');
            var $person = $(
                '<span><img src="' + $img + '" class="img-flag" /> ' + person.text + '</span>'
            );
            return $person;
        }

        $('.heyat-select2-person').select2({
            templateResult: formatPerson,
            templateSelection: formatPerson,
            width: '100%',
            dir: 'rtl',
            multiple: true
        });
    }

    if ($('#heyat_event_date_picker').length > 0) {
        $('#heyat_event_date_picker').persianDatepicker({
            initialValue: false,
            format: 'YYYY-MM-DD',
            autoClose: true,
            observer: true
        });
    }

});
