import $ from 'jquery';

$(document).ready(() => {
    var nameHelp = $('#nameHelp'),
        inputName = $('#inputName'),
        emailHelp = $('#emailHelp'),
        inputEmail = $('#inputEmail'),
        telHelp = $('#telHelp'),
        inputPhone = $('#inputPhone'),
        passHelp = $('#passHelp'),
        inputPass = $('#inputPass'),
        authEmail = $('#authEmail'),
        authPass = $('#authPass'),
        inputBookName = $('#inputBookName'),
        bookNameHelp = $('#bookNameHelp'),
        inputBookAuthor = $('#inputBookAuthor'),
        bookAuthorHelp = $('#bookAuthorHelp'),
        inputBookDate = $('#inputBookDate'),
        bookDateHelp = $('#bookDateHelp'),
        inputEditName = $('#inputEditName'),
        editNameHelp = $('#editNameHelp'),
        inputEditAuthor = $('#inputEditAuthor'),
        editAuthorHelp = $('#editAuthorHelp'),
        inputEditDate = $('#inputEditDate'),
        editDateHelp = $('#editDateHelp');

    $('#regForm').submit(function (e) {
        e.preventDefault();

        let regFlag = true;

        let name = inputName.val().trim(),
            email = inputEmail.val().trim(),
            tel = inputPhone.val().trim(),
            pass = inputPass.val().trim();

        if (name === '') {
            regFlag = false;
            nameHelp.text('Вы не ввели ФИО!');
            nameHelp.addClass('invalid');
            inputName.addClass('invalid-input');
        }
        else {
            nameHelp.text('Мы никогда не поделимся вашими данными с кем-либо еще.');
            nameHelp.removeClass('invalid');
            inputName.removeClass('invalid-input');
        }

        if (email === '') {
            regFlag = false;
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
            regFlag = false;
            telHelp.text('Вы не ввели телефон!');
            telHelp.addClass('invalid');
            inputPhone.addClass('invalid-input');
        }
        else {
            telHelp.text('Мы никогда не поделимся вашим телефоном с кем-либо еще.');
            telHelp.removeClass('invalid');
            inputPhone.removeClass('invalid-input');
        }

        if (pass === '') {
            regFlag = false;
            passHelp.text('Вы не ввели пароль!');
            passHelp.addClass('invalid');
            inputPass.addClass('invalid-input');
        }
        else {
            passHelp.text('Мы никогда не поделимся вашими данными с кем-либо еще. Минимальная длина - 6 символов.');
            passHelp.removeClass('invalid');
            inputPass.removeClass('invalid-input');
        }

        let nameRegex = new RegExp('^[А-Яа-яЁё\\s + -]+$');
        if (!nameRegex.test(name)) {
            regFlag = false;
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
            regFlag = false;
            emailHelp.text('Вы ввели email в неверном формате !');
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
            regFlag = false;
            telHelp.text('Вы ввели телефон в неправильном формате.');
            telHelp.addClass('invalid');
            inputPhone.addClass('invalid-input');
        }
        else {
            telHelp.text('Мы никогда не поделимся вашим телефоном с кем-либо еще.');
            telHelp.removeClass('invalid');
            inputPhone.removeClass('invalid-input');
        }

        if (pass.length < 6) {
            regFlag = false;
            passHelp.text('Минимальная длина пароля - 6 символов!');
            passHelp.addClass('invalid');
            inputPass.addClass('invalid-input');
        }
        else {
            passHelp.text('Мы никогда не поделимся вашими данными с кем-либо еще. Минимальная длина - 6 символов.');
            passHelp.removeClass('invalid');
            inputPass.removeClass('invalid-input');
        }

        if (regFlag) {
            let formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('tel', tel);
            formData.append('pass', pass);

            $.ajax({
                url: '/registration/send',
                type: 'POST',
                processData: false,
                dataType: 'json',
                contentType: false,
                cache: false,
                data: formData,
                success(data) {
                    if (data['status'] === false) {
                        emailHelp.text('Такой email уже зарегистрирован!');
                        emailHelp.addClass('invalid');
                        inputEmail.addClass('invalid-input');
                    }
                    else {
                        emailHelp.text('Мы никогда не поделимся вашей электронной почтой с кем-либо еще.');
                        emailHelp.removeClass('invalid');
                        inputEmail.removeClass('invalid-input');
                        alert(data['msg']);
                        window.location.href = '/';
                    }
                }
            });
        }
    });

    $('#exitLink').click(function () {
        $.ajax({
            url: '/exit',
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            success(data) {
                alert(data['msg']);
                window.location.href = '/';
            }
        })
    });

    $('#authForm').submit(function (e) {
        e.preventDefault();

        let email = authEmail.val().trim(),
            pass = authPass.val().trim();

        let formData = new FormData();
        formData.append('email', email);
        formData.append('pass', pass);

        $.ajax({
            url: '/auth/login',
            type: 'POST',
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            data: formData,
            success(data) {
                alert(data['msg']);
                if (data['status'] === false) {
                    if (data['field'] === 'email') {
                        authEmail.addClass('invalid-input');
                        authPass.removeClass('invalid-input');
                    }
                    else if (data['field'] === 'pass') {
                        authPass.addClass('invalid-input');
                        authEmail.removeClass('invalid-input');
                    }
                }
                else {
                    authEmail.removeClass('invalid-input');
                    authPass.removeClass('invalid-input');
                    window.location.href = '/';
                }
            }
        });
    });

    var image = null, file = null, editImage = null, editFile = null;

    $('#formImage').change(function (e) {
        image = e.target.files[0];
    })

    $('#formFile').change(function (e) {
        file = e.target.files[0];
    })

    $('#addForm').submit(function (e) {
        e.preventDefault();

        let name = inputBookName.val().trim(),
            author = inputBookAuthor.val().trim(),
            date = inputBookDate.val().trim();

        let addFlag = true;

        if (name === '') {
            addFlag = false;
            bookNameHelp.text('Вы не ввели название книги');
            bookNameHelp.addClass('invalid');
            inputBookName.addClass('invalid-input');
        }
        else {
            bookNameHelp.text('');
            bookNameHelp.removeClass('invalid');
            inputBookName.removeClass('invalid-input');
        }

        if (author === '') {
            addFlag = false;
            bookAuthorHelp.text('Вы не ввели имя автора');
            bookAuthorHelp.addClass('invalid');
            inputBookAuthor.addClass('invalid-input');
        }
        else {
            bookAuthorHelp.text('');
            bookAuthorHelp.removeClass('invalid');
            inputBookAuthor.removeClass('invalid-input');
        }

        if (date === '') {
            addFlag = false;
            bookDateHelp.text('Вы не указали дату прочтения');
            bookDateHelp.addClass('invalid');
            inputBookDate.addClass('invalid-input');
        }
        else {
            bookDateHelp.text('');
            bookDateHelp.removeClass('invalid');
            inputBookDate.removeClass('invalid-input');
        }

        let formData = new FormData();
        formData.append('name', name);
        formData.append('author', author);
        formData.append('date', date);

        if (image != null) {
            formData.append('image', image);
        }

        if (file != null) {
            formData.append('file', file);
        }

        $.ajax({
            url: '/add/book',
            type: 'POST',
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            data: formData,
            success(data) {
                window.location.href = '/';
            }
        });
    });

    $('#formImageEdit').change(function (e) {
        editImage = e.target.files[0];
    })

    $('#formFileEdit').change(function (e) {
        editFile = e.target.files[0];
    })

    $('#deletePoster').click( function () {
        if (editImage === null) {
            editImage = false;
        }
    });

    $('#deleteFile').click( function () {
        if (editFile === null) {
            editFile = false;
        }
    });

    $('#editForm').submit(function (e) {
        e.preventDefault();

        let name = inputEditName.val().trim(),
            author = inputEditAuthor.val().trim(),
            date = inputEditDate.val().trim(),
            id = $('#editButton').attr('data-id');

        let editFlag = true;

        if (name === '') {
            editFlag = false;
            editNameHelp.text('Вы не ввели название книги');
            editNameHelp.addClass('invalid');
            inputEditName.addClass('invalid-input');
        }
        else {
            editNameHelp.text('');
            editNameHelp.removeClass('invalid');
            inputEditName.removeClass('invalid-input');
        }

        if (author === '') {
            editFlag = false;
            editAuthorHelp.text('Вы не ввели имя автора');
            editAuthorHelp.addClass('invalid');
            inputEditAuthor.addClass('invalid-input');
        }
        else {
            editAuthorHelp.text('');
            editAuthorHelp.removeClass('invalid');
            inputEditAuthor.removeClass('invalid-input');
        }

        if (date === '') {
            editFlag = false;
            editDateHelp.text('Вы не указали дату прочтения');
            editDateHelp.addClass('invalid');
            inputEditDate.addClass('invalid-input');
        }
        else {
            editDateHelp.text('');
            editDateHelp.removeClass('invalid');
            inputEditDate.removeClass('invalid-input');
        }

        if(editFlag) {
            let formData = new FormData();
            formData.append('name', name);
            formData.append('author', author);
            formData.append('date', date);

            if (editImage != null) {
                formData.append('editImage', editImage);
            }

            if (editFile != null) {
                formData.append('editFile', editFile);
            }

            console.log(Object.fromEntries(formData));

            $.ajax({
                url: '/editing/book/' + id,
                type: 'POST',
                processData: false,
                dataType: 'json',
                contentType: false,
                cache: false,
                data: formData,
                success(data) {
                    alert(data['msg']);
                    window.location.href = '/';
                }
            });
        }
    });
});
