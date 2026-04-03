function Validator(formElement, options = {}) {
    var validatorRules = {
        required: function (value) {
            return value && value.trim() ? undefined : "Trường này không được bỏ trống !";
        },
        email: function (value) {
            var regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            return regex.test(value) ? undefined : "Email phải là một địa chỉ email hợp lệ";
        },
        cccd: function (value) {
            var regex = /^\d{12}$/; // CCCD đúng 12 số
            return regex.test(value) ? undefined : "Định dạng căn cước không hợp lệ (12 chữ số)";
        }
    };

    var formRules = {};

    if (formElement) {
        var inputs = formElement.find('[name][rules]');

        $.each(inputs, function (index, input) {
            var $input = $(input);
            var name = $input.attr('name');
            var rules = $input.attr('rules').split('|');

            $.each(rules, function (i, rule) {
                // Kiểm tra nếu rule tồn tại trong hệ thống
                if (validatorRules[rule]) {
                    if (Array.isArray(formRules[name])) {
                        formRules[name].push(validatorRules[rule]);
                    } else {
                        formRules[name] = [validatorRules[rule]];
                    }
                }
            });

            // Lắng nghe sự kiện để validate real-time
            $input.on('blur', function () { handleValidate($input); });
            $input.on('input', function () { handleClearError($input); });
        });
    }

    function getParent(element) {
        // Tìm thẻ bao ngoài gần nhất có chứa .form-message (thường là .input-group hoặc .col)
        return element ? element.closest('.input-group, .col, .form-group') : null;
    }

    function handleClearError(input) {
        var parent = getParent(input);
        input.removeClass('invalid');
        if (parent) {
            parent.find('.form-message').text('');
        }
    }

    function handleValidate(input) {
        var value = input.val();
        var name = input.attr('name');
        var rules = formRules[name];
        var errMessage;

        if (!rules) return true;

        // Chạy qua các rules, lấy lỗi đầu tiên tìm thấy
        for (var rule of rules) {
            errMessage = rule(value);
            if (errMessage) break;
        }

        var parent = getParent(input);
        if (errMessage) {
            if (parent) {
                parent.find('.form-message').text(errMessage);
            }
            input.addClass('invalid');
        } else {
            handleClearError(input);
        }
        return !errMessage;
    }

    // XỬ LÝ SUBMIT
    formElement.off('submit').on('submit', function (event) {
        event.preventDefault();
        var isFormValid = true;
        var inputs = formElement.find('[name][rules]');

        $.each(inputs, function (i, input) {
            var $input = $(input);
            if (!handleValidate($input)) {
                isFormValid = false;
            }
        });

        if (isFormValid) {
            // Sửa lỗi thiếu 'if' ở đây
            if (typeof options.onSubmit === 'function') {
                var formValues = {};
                var allInputs = formElement.find("[name]");

                $.each(allInputs, function (index, inputNode) {
                    var $inputNode = $(inputNode);
                    formValues[$inputNode.attr('name')] = $inputNode.val();
                });

                options.onSubmit(formValues);
            } else {
                // Nếu không có hàm onSubmit tùy chỉnh, gửi form mặc định
                formElement.get(0).submit();
            }
        }
    });
}