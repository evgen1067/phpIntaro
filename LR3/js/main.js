$(document).ready(() => {
    var nameHelp = $('#nameHelp'),
        inputName = $('#inputName'),
        emailHelp = $('#emailHelp'),
        inputEmail = $('#inputEmail'),
        telHelp = $('#telHelp'),
        inputPhone = $('#inputPhone'),
        commentTextarea = $('#commentTextarea'),
        commentHelp = $('#commentHelp'),
        nameAlert = $('#nameAlert'),
        emailAlert = $('#emailAlert'),
        telAlert = $('#telAlert'),
        commentAlert = $('#commentAlert'),
        dateAlert = $('#dateAlert'),
        modalAlert = $('#modalAlert');

    $('.collapse-btn').click(function () {
        modalAlert.toggle();
    });

    $('#feedbackForm').submit(function (e) {
        e.preventDefault();

        let feedbackFlag = true;

        let name = inputName.val().trim(),
            email = inputEmail.val().trim(),
            tel = inputPhone.val().trim(),
            comment = commentTextarea.val().trim();

        if (name === '') {
            feedbackFlag = false;
            nameHelp.text('Вы не ввели имя!');
            nameHelp.addClass('invalid');
            inputName.addClass('invalid-input');
        }
        else {
            nameHelp.text('Мы никогда не поделимся вашими данными с кем-либо еще.');
            nameHelp.removeClass('invalid');
            inputName.removeClass('invalid-input');
        }

        if (email === '') {
            feedbackFlag = false;
            emailHelp.text('Вы не ввели email!');
            emailHelp.addClass('invalid');
            inputEmail.addClass('invalid-input');
        }
        else {
            emailHelp.text('Мы никогда не поделимся вашей электронной почтой с кем-либо еще.');
            emailHelp.removeClass('invalid');
            inputEmail.removeClass('invalid-input');
        }

        if (tel === '') {
            feedbackFlag = false;
            telHelp.text('Вы не ввели телефон!');
            telHelp.addClass('invalid');
            inputPhone.addClass('invalid-input');
        }
        else {
            telHelp.text('Мы никогда не поделимся вашим телефоном с кем-либо еще.');
            telHelp.removeClass('invalid');
            inputPhone.removeClass('invalid-input');
        }

        if (comment === '') {
            commentHelp.text('Вы не ввели сообщение!');
            commentHelp.addClass('invalid');
            commentTextarea.addClass('invalid-input');
        }
        else {
            commentHelp.text('Мы никогда не поделимся вашим сообщением с кем-либо еще.');
            commentHelp.removeClass('invalid');
            commentTextarea.removeClass('invalid-input');
        }

        let nameRegex = new RegExp('^[А-Яа-яЁё\\s + -]+$');
        if (!nameRegex.test(name)) {
            feedbackFlag = false;
            nameHelp.text('Вы ввели имя в неверном формате! Допустимы только русские буквы');
            nameHelp.addClass('invalid');
            inputName.addClass('invalid-input');
        }
        else {
            nameHelp.text('Мы никогда не поделимся вашими данными с кем-либо еще.');
            nameHelp.removeClass('invalid');
            inputName.removeClass('invalid-input');
        }

        let emailRegex = new RegExp('^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}$');
        if (!emailRegex.test(email)) {
            feedbackFlag = false;
            emailHelp.text('Вы ввели email в неверном формате!');
            emailHelp.addClass('invalid');
            inputEmail.addClass('invalid-input');
        }
        else {
            emailHelp.text('Мы никогда не поделимся вашей электронной почтой с кем-либо еще.');
            emailHelp.removeClass('invalid');
            inputEmail.removeClass('invalid-input');
        }

        let telRegex = new RegExp('^(\\+7|7|8)?[\\s\\-]?\\(?[489][0-9]{2}\\)?[\\s\\-]?[0-9]{3}[\\s\\-]?[0-9]{2}[\\s\\-]?[0-9]{2}$');
        if (!telRegex.test(tel)) {
            feedbackFlag = false;
            telHelp.text('Вы ввели телефон в неправильном формате.');
            telHelp.addClass('invalid');
            inputPhone.addClass('invalid-input');
        }
        else {
            telHelp.text('Мы никогда не поделимся вашим телефоном с кем-либо еще.');
            telHelp.removeClass('invalid');
            inputPhone.removeClass('invalid-input');
        }

        if (feedbackFlag) {
            let formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('tel', tel);
            formData.append('comment', comment);

            $.ajax({
                url: 'src/db.php',
                type: 'POST',
                processData: false,
                dataType: 'json',
                contentType: false,
                cache: false,
                data: formData,
                success(data) {
                        if (data['date'] !== null && data['time'] === null) {
                        nameAlert.text('Имя: ' + name);
                        emailAlert.text('Email: ' + email);
                        telAlert.text('Телефон: ' + tel);
                        dateAlert.text('С Вами свяжутся после : ' + data['date']);
                        commentAlert.text('Сообщение: ' + comment);
                        modalAlert.toggle();
                    }
                    else {
                        let message = 'Осталось подождать: ' + data['time'] + ' минут';
                        alert(message);
                    }
                }
            })
        }
    });
});