<?php
namespace RedirMe;
/**
* Translations from English to Russian
*/
class Translation {

	public static $translations = array(
		'1' => array(
		// Setup
			'Installation' => 'Установка',
			'Beginning Installation' => 'Начало установки',
			'Welcome to the Installation Master!' => 'Добро пожаловать в мастер установки!',
			'The server configuration is fine. Now we can proceed with the installation. To do this, click the button below.' => 'Конфигурация сервера впорядке. Теперь мы можем продолжить установку. Для этого нажмите кнопку ниже.',
			'Your version of PHP should be at least' => 'Ваша версия PHP должна быть не ниже',
			'Step 1 - Connecting to a database' => 'Шаг 1 - Соединение с базой данных',
			'Now you need to enter the data to connect to the MySQL database.' => 'Сейчас Вам нужно ввести данные для соединения с базой данных MySQL',
			'Error connection with the database!' => 'Ошибка соединения с базой данных!',
			'Database Host' => 'Сервер базы данных',
			'Database User' => 'Пользователь базы данных',
			'Database Name' => 'Имя базы данных',
			'Database Password' => 'Пароль базы данных',
			'Table Prefix (optional)' => 'Префикс таблиц (опционально)',
			'Step 2 - Setting up parameters' => 'Шаг 2 - Настройка параметров',
			'Congratulations! We managed to connect to the database. Now it remains to configure several parameters.' => 'Поздравляем! Нам удалось соединиться с базой данных. Теперь осталось настроить несколько параметров.',
			'Admin Username' => 'Имя пользователя администратора',
			'Admin E-mail' => 'Эл. адрес администратора',
			'Admin Password' => 'Пароль администратора',
			'System Language' => 'Язык системы',
			'Install' => 'Установить',
			'Enter Your Username' => 'Введите Ваше имя пользователя',
			'Username must consist only of Latin symbols and digits' => 'Имя пользователя должно состоять только из латинских символов и цифр',
			'Enter Your E-mail' => 'Введите Ваш эл. адрес',
			'Incorrect E-mail' => 'Некорректный эл. адрес',
			'Enter Your Password' => 'Введите Ваш пароль',
			'Error in the installation process' => 'Ошибка в процессе установки',
		// Auth
			'Authorization' => 'Авторизация',
			'E-mail' => 'Эл. почта',
			'Password' => 'Пароль',
			'Forgot password?' => 'Забыли пароль?',
			'Remember me' => 'Запомнить меня',
			'From' => 'От',
			'to' => 'до',
			'Answer in digits' => 'Ответьте цифрами',
			'Log In' => 'Войти',
			'You was successfully logged out from the System' => 'Вы успешно вышли из системы',
			'Your account is locked' => 'Ваш аккаунт заблокирован',
			'Error in calculating' => 'Ошибка в вычислении',
			'Enter Your Username' => 'Введите Ваше имя пользователя',
			'Enter Your Password' => 'Введите Ваш пароль',
			'Incorrect password length' => 'Неправильная длина пароля',
			'Error in username or in password' => 'Ошибка в имени пользователя или пароле',
			'Non-existent notice' => 'Несуществующее уведомление',
			'Password Recovery' => 'Восстановление пароля',
			'Enter Your E-mail' => 'Введите Ваш эл. адрес',
			'Incorrect E-mail' => 'Некорректный эл. адрес',
			'A user with such data doesn\'t exists in the system' => 'Пользователя с такими данными не существует в системе',
			'New password sent to your e-mail' => 'Новый пароль отправлен на ваш эл. адрес',
			'Error restoring Your password. Please, try again.' => 'Ошибка восстановления Вашего пароля. Пожалуйста, попробуйте еще раз.',
			'Submit' => 'Отправить',
			'Restoring the password' => 'Восстановление пароля',
			'Your new password:' => 'Ваш новый пароль:',
		// Digits
			'one' => 'один',
			'two' => 'два',
			'three' => 'три',
			'four' => 'четыре',
			'five' => 'пять',
			'six' => 'шесть',
			'seven' => 'семь',
			'eight' => 'восемь',
			'nine' => 'девять',
			'ten' => 'десять',
			'eleven' => 'одиннадцать',
			'twelve' => 'двенадцать',
			'thirteen' => 'тринадцать',
			'fourteen' => 'четырнадцать',
			'fifteen' => 'пятнадцать',
			'sixteen' => 'шестнадцать',
			'seventeen' => 'семнадцать',
			'eighteen' => 'восемнадцать',
			'nineteen' => 'девятнадцать',
			'twenty' => 'двадцать',
		// Main
			'A new version available' => 'Доступна новая версия',
			'Update System' => 'Обновлять систему',
			'Main menu' => 'Главное меню',
			'Dashboard' => 'Консоль',
			'Users' => 'Пользователи',
			'Settings' => 'Настройки',
			'My Profile' => 'Мой профиль',
			'Log Out' => 'Выйти',
			'Create' => 'Создать',
			'No updates' => 'Обновлений нет',
			'Categories' => 'Категории',
			'Without Category' => 'Без категории',
		// Misc
			'Page not found' => 'Страница не найдена',
			'Sorry, but the requested page was not found. Use the navigation menu.' => 'Извините, но запрашиваемая страница не найдена. Воспользуйтесь навигационным меню.',
			'Access Denied' => 'Доступ запрещен',
			'You don\'t have permissions to view this page' => 'У вас нет прав для просмотра этой страницы',
			'Copyrights' => 'Авторские права',
			'Docs and Support' => 'Документация и поддержка',
			'at' => 'в',
		// Console
			'Search links in all categories' => 'Поиск ссылок во всех категориях',
			'General Statistics' => 'Общая статистика',
			'Amount' => 'Количество',
			'The ratio of the number of clicks by each link' => 'Соотношение количества кликов по каждой ссылке',
			'Clicks Amount' => 'Количество кликов',
			'The ratio of the number of links by category' => 'Соотношение количества ссылок по категориям',
			'Links Amount' => 'Количество ссылок',
		// Links
			'Links' => 'Ссылки',
			'Create Link' => 'Создать ссылку',
			'Search Links' => 'Поиск ссылок',
			'Title' => 'Название',
			'Required!' => 'Обязательно!',
			'Alias' => 'Псевдоним',
			'Add a File' => 'Добавить файл',
			'Target Page' => 'Целевая страница',
			'Leave this field empty, if you want to use the ID as alias or write some text and people will see this text in their browsers. Use only lowercase English letters and numbers!' => 'Оставьте это поле пустым, если вы хотите использовать идентификатор в качестве алиаса, или напишите какой-нибудь текст, и тогда люди увидят его в своих браузерах. Используйте только строчные английские символы и цифры!',
			'Link URL address' => 'URL-адрес ссылки',
			'Specify the target page to be redirected to.' => 'Укажите целевую страницу для перенаправления.',
			'For example' => 'Например',
			'Category' => 'Категория',
			'Type of redirect' => 'Тип перенаправления',
			'301 - Moved Permanently' => '301 - Перемещен постоянно',
			'302 - Moved Temporarily' => '302 - Перемещен временно',
			'Extra Features' => 'Дополнительные функции',
			'Not Selected' => 'Не выбрано',
			'Redirect with the delay' => 'Перенаправление с задержкой',
			'Password Protection' => 'Защита паролем',
			'Delay Page' => 'Страница задержки',
			'Specify the page that will be displayed while the timer counts. It can be a page advertising any of your projects or products. If a person will likes the content of the page, he can visit this page by clicking special link, or he will be redirected to the target page when the timer will end the count.' => 'Укажите страницу, которая будет отображаться во время отсчета таймера. Это может быть страница, рекламирующая любой из ваших проектов или продуктов. Если человеку понравится содержимое страницы, он сможет посетить эту страницу, нажав специальную ссылку, или он будет перенаправлен на целевую страницу, когда таймер закончит отсчет.',
			'Delay time on the page (in seconds)' => 'Время задержки на странице (в секундах)',
			'Link to the file' => 'Ссылка на файл',
			'Password not set' => 'Пароль не задан',
			'Fill this field if You want set new' => 'Заполните это поле, если хотите задать новый',
			'Enter the title' => 'Введите название',
			'The alias should consist only of Latin characters, numbers, dashes and underscores.' => 'Псевдоним должен состоять только из латинских символов, цифр, тире и подчеркиваний.',
			'The link with such alias already exists in the system' => 'Ссылка с таким псевдонимом уже есть в системе',
			'Set the password' => 'Задайте пароль',
			'Enter the target page URL' => 'Введите адрес целевой страницы',
			'Enter the delay page URL' => 'Введите адрес страницы задержки',
			'Error creating the link' => 'Ошибка создания ссылки',
			'Edit Link' => 'Редактировать ссылку',
			'The link was successfully created' => 'Ссылка создана успешно',
			'The link was successfully updated' => 'Ссылка обновлена успешно',
			'Error updating the link' => 'Ошибка обновления ссылки',
			'Update' => 'Обновить',
		// Redirecting with the delay
			'Redirecting...' => 'Перенаправление...',
			'You will be redirected after' => 'Вы будете перенаправлены через',
			'sec' => 'сек',
			'wait...' => 'подождите...',
			'Visit the page below' => 'Посетить страницу ниже',
		// Processing Link Password
			'Processing Redirect Password' => 'Обработка пароля перенаправления',
			'Password Required' => 'Требуется пароль',
			'Please, enter the password of the redirect to see the target page.' => 'Пожалуйста, введите пароль перенаправления, чтобы увидеть целевую страницу.',
			'Wrong Password' => 'Неправильный пароль',
		// File Picker
			'File Picker' => 'Сборщик файлов',
			'Close' => 'Закрыть',
			'Upload' => 'Загрузить',
			'Upload file(s). Max file size:' => 'Загрузить файл(ы). Максимальный размер:',
			'Uploading...' => 'Загрузка...',
			'The file has an unsupported format. For security reasons, we can\'t upload it.' => 'Файл имеет неподдерживаемый формат. Из соображений безопасности, мы не можем загрузить его.',
			'was successfully uploaded' => 'был успешно загружен',
			'Error server answer' => 'Ошибка ответа сервера',
			'Error AJAX request' => 'Ошибка AJAX-запроса',
			'Library' => 'Библиотека',
			'Delete Permanently' => 'Удалить навсегда',
			'The file was successfully removed. Expect...' => 'Файл успешно удален. Ожидайте...',
			'Insert' => 'Вставить',
			'Error sending the request' => 'Ошибка отправки запроса',
		// In category
			'Edit Category' => 'Редактировать категорию',
			'No links in the category' => 'Нет ссылок в категории',
			'No search results' => 'Нет результатов поиска',
			'Search...' => 'Поиск...',
			'Author' => 'Автор',
			'Clicks' => 'Клики',
			'Copy' => 'Скопировать',
			'The link URL address was successfully copied to clipboard!' => 'URL-адрес ссылки успешно скопирован в буфер обмена!',
			'Error copying to clipboard' => 'Ошибка копирования в буфер обмена',
			'Please, select links to remove' => 'Пожалуйста, выделите ссылки для удаления',
			'You do not have permission to perform this operation' => 'У вас нет разрешения для выполнения этой операции',
			'Error removing links' => 'Ошибка удаления ссылок',
			'Selected links were successfully removed' => 'Выбранные ссылки успешно удалены',
		// Category Add/Edit
			'Create Category' => 'Создать категорию',
			'Error creating the category' => 'Ошибка создания категории',
			'The category was created successfully' => 'Категория создана успешно',
			'Parent Category' => 'Родительская категория',
			'None' => 'Нет',
			'Remove Permanently' => 'Удалить навсегда',
			'Parent category must be a number' => 'Родительская категория должна быть числом',
			'The category was successfully updated' => 'Категория успешно обновлена',
			'Error updating the category' => 'Ошибка обновления категории',
			'The category was successfully removed' => 'Категория успешно удалена',
			'Error removing the category' => 'Ошибка удаления категории',
		// User List
			'Username' => 'Имя пользователя',
			'Name' => 'Имя',
			'Registration Date' => 'Дата регистрации',
			'No created users' => 'Нет созданных пользователей',
			'Please, select users to remove' => 'Пожалуйста, выберите пользователей для удаления',
			'Selected users were successfully removed' => 'Выбранные пользователи успешно удалены',
			'Error removing users' => 'Ошибка удаления пользователей',
		// User Add/Edit
			'Create User' => 'Создать пользователя',
			'Permissions' => 'Разрешения',
			'Enter Username' => 'Введите имя пользователя',
			'The username must consist only of Latin characters and numbers' => 'Имя пользователя должно состоять только из латинских символов и цифр',
			'The user with such username already exists in the system' => 'Пользователь с таким именем пользователя уже есть в системе',
			'Enter E-mail' => 'Введите эл. адрес',
			'Incorrect E-mail' => 'Некорректный эл. адрес',
			'The user with such e-mail already exists in the system' => 'Пользователь с таким электронным адресом уже есть в системе',
			'Enter First Name' => 'Введите имя',
			'Enter Last Name' => 'Введите фамилию',
			'Error creating a user' => 'Ошибка создания пользователя',
			'Edit User' => 'Редактировать пользователя',
			'Send new password' => 'Отправить новый пароль',
			'A new user was successfully created. The password was sent to him by e-mail.' => 'Новый пользователь успешно создан. Пароль выслан ему на электронную почту.',
			'The user data was successfully updated' => 'Данные пользователя успешно обновлены',
			'Error updating the user data' => 'Ошибка обновления данных пользователя',
			'A new password has sent to the user successfully' => 'Новый пароль успешно отправлен пользователю',
			'Error sending a new password' => 'Ошибка отправки нового пароля',
		// User Permissions
			'Manage Personal Profile' => 'Управлять личным профилем',
			'Manage Users' => 'Управлять пользователями',
			'Remove Users' => 'Удалять пользователей',
			'Change System Settings' => 'Изменять настройки системы',
			'Manage Categories' => 'Управлять категориями',
			'Remove Categories' => 'Удалять категории',
			'Manage Links' => 'Управлять ссылками',
			'Remove Links' => 'Удалять ссылки',
			'Manage the items of all users' => 'Управлять материалами всех пользователей',
		// Profile
			'can\'t be changed' => 'не изменяется',
			'First Name' => 'Имя',
			'Last Name' => 'Фамилия',
			'New Password' => 'Новый пароль',
			'Repeat New Password' => 'Повторите новый пароль',
			'Demo mode is enabled. You can not change the settings.' => 'Включен демо-режим. Менять настройки нельзя.',
			'Enter your e-mail' => 'Введите ваш электронный адрес',
			'Incorrect e-mail' => 'Некорректный электронный адрес',
			'Enter your first name' => 'Введите ваше имя',
			'Enter your last name' => 'Введите вашу фамилию',
			'Passwords do not match' => 'Пароли не совпадают',
			'Your profile was successfully updated' => 'Ваш профиль успешно обновлен',
			'Error updating your profile' => 'Ошибка обновления вашего профиля',
		// Settings
			'System URL Address' => 'URL-адрес системы',
			'URL address of redirect from the home page' => 'URL-адрес перенаправления с главной страницы',
			'Specify the address of the page to which you want to redirect the visitor who came to the main page of the system (not the admin panel). If you leave the field blank, redirection will take place to the admin panel.' => 'Укажите адрес страницы, на которую хотите перенаправить посетителя, который зашел на главную страницу системы (не админ-панели). Если оставить поле пустым, перенаправление будет происходить в админ-панель.',
			'System Language' => 'Язык системы',
			'Set the system language according to the browser language' => 'Устанавливать язык системы в соответствии с языком браузера',
			'Enable this option if you want the system to determine the language of the user\'s browser and install it for him. In this case, the language from the list of available in the system will be selected, or if there is no such language in the system, the one you installed as the system language will be selected.' => 'Включите эту опцию, если хотите, чтобы система сама определяла язык браузера пользователя и устанавливала его для него. В этом случае будет выбран язык из списка имеющихся в системе, либо, если в системе нет такого языка, то будет выбран тот, который вы установили в качестве системного.',
			'Date Format' => 'Формат даты',
			'Time Format' => 'Формат времени',
			'On' => 'Вкл.',
			'Off' => 'Выкл.',
			'The system settings were successfully updated' => 'Настройки системы успешно обновлены',
			'Error updating the system settings' => 'Ошибка обновления настроек системы',
		// Updating
			'The system was updated successfully!' => 'Система успешно обновлена!',
			'Finish it' => 'Завершить',
		),
		'2' => array(
			'symbols' => 'символов',
			'seconds' => 'секунд',
			'Link' => 'Ссылку',
			'Category' => 'Категорию',
			'User' => 'Пользователя',
			'First' => 'Первая',
			'Prev' => 'Предыдущая',
			'Next' => 'Следующая',
			'Last' => 'Последняя',
			'Update System' => 'Обновить систему',
		),
		'3' => array(
			
		),
		'4' => array(
			'Remove Selected' => 'Удалить выбранные',
		),
		'5' => array(
			'Remove Selected' => 'Удалить выбранных',
		),
	);

}

?>