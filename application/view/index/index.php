<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 18:50
 */
/**
 * @var $this \Controller\IndexController
 */
?>

<form id="searchForm">
    <div class="form-group">
        <label>Site url:</label>
        <input type="text" class="form-control" name="SearchForm[url]" placeholder="http://test.com" required
               value="http://bash.im">
    </div>

    <div class="form-group">
        <label>Content type:</label>
        <div>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary active">
                    <input type="radio" name="SearchForm[type]" value="links" checked> Links
                </label>
                <label class="btn btn-primary">
                    <input type="radio" name="SearchForm[type]" value="images"> Images
                </label>
                <label class="btn btn-primary">
                    <input type="radio" name="SearchForm[type]" value="text"> Text
                </label>
            </div>
        </div>
    </div>

    <div id="textSearchWrapper" style="display: none;" class="form-group">
        <input type="text" class="form-control" name="SearchForm[text]" placeholder="Search text...">
    </div>
    <div class="form-group" id="errorsList" style="color:red;display: none;">

    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-default" disabled>Submit</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        var searchForm = $('#searchForm');
        var subBtn = searchForm.find('[type="submit"]');

        function validateForm(form) {
            var errors = [];
            if (!validateUrl(form.find('[name="SearchForm[url]"]').val())) {
                errors.push('Invalid site URL');
            }

            var textField = form.find('[name="SearchForm[text]"]');
            if (textField.is('[required]') && !textField.val()) {
                errors.push('Invalid or empty search text value');
            }

            if (errors.length) {
                $('#errorsList').html(errors.join('<br/>')).show();
                return false;
            } else {
                $('#errorsList').html(errors.join('<br/>')).hide();
                return true;
            }
        }

        function validateUrl(value) {
            return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(value);
        }

        searchForm.find('[name="SearchForm[type]"]').change(function () {
            if (this.value === 'text') {
                $('#textSearchWrapper').show();
                $('#textSearchWrapper input').attr('required', true);
            } else {
                $('#textSearchWrapper').hide();
                $('#textSearchWrapper input').removeAttr('required');
            }
        });

        searchForm.on('submit', function (e) {
            e.preventDefault();
            if (validateForm(searchForm)) {
                $.ajax({
                    url: '/index/parse',
                    method: 'POST',
                    data: searchForm.serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        searchForm.find('[type="submit"]').attr('disabled', true);
                    },
                    success: function (data) {
                        if (typeof data['success'] !== 'undefined' && data.success === true) {
                            window.location = '/index/results';
                        } else if (typeof data.errors !== 'undefined') {
                            var errstr = '';
                            for (var i in data.errors) {
                                errstr += data.errors[i] + '<br/>';
                            }
                            $('#errorsList').html(errstr).show();
                        }

                        searchForm.find('[type="submit"]').removeAttr('disabled');
                    }
                });
            }
        });

        searchForm.find('input').on('change', function () {
            if (validateForm(searchForm)) {
                subBtn.removeAttr('disabled');
            } else {
                subBtn.attr('disabled', true);
            }
        });

    });
</script>
