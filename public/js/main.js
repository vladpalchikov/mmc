$(document).ready(function($) {
	$('input[name="phone"]').mask("8 999 999-99-99");
	$('input[name="person_document_phone"]').mask("8 999 999-99-99");
	$('input[name="organization_contact_phone"]').mask("8 999 999-99-99");
    var select = $("select").not('.nos2').select2({
		dropdownAutoWidth : false,
		width: "resolve",
		theme: "bootstrap",
		language: "ru"
	});

	$('.clients').select2({
		dropdownAutoWidth : false,
		width: "resolve",
		theme: "bootstrap",
		language: "ru",
        allowClear: true,
        placeholder: "Не указан",
		dropdownParent: $(".new-service-modal"),
		ajax: {
			url: '/ajax/clients',
			dataType: 'json',
			processResults: function (data) {
                return {
                    results: data
                };
            },
		}
	});

	$('#host_id').select2({
		dropdownAutoWidth : false,
		width: "resolve",
		theme: "bootstrap",
		language: "ru",
        allowClear: true,
        placeholder: "Не указан",
		ajax: {
			url: '/ajax/clients',
			dataType: 'json',
			processResults: function (data) {
                return {
                    results: data
                };
            },
		}
	});

    var select = $(".selectModal").select2({
		dropdownAutoWidth : false,
		width: "100%",
		theme: "bootstrap",
        dropdownParent: $(".new-service-modal"),
		language: "ru"
	});

	$('input[name="daterange"]').daterangepicker({
		'alwaysShowCalendars': true,
		"singleDatePicker": $('input[name="daterange"]').data('single'),
		// "minDate": moment().subtract(3, 'month'),
		ranges: {
           'Сегодня': [moment(), moment()],
           'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
           'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
           'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
           'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "locale": {
	        "format": "DD.MM.YY",
	        "separator": "-",
	        "applyLabel": "Применить",
	        "cancelLabel": "Отмена",
	        "fromLabel": "От",
	        "toLabel": "До",
	        "weekLabel": "Н",
	        "customRangeLabel": "Свой диапазон",
	        "daysOfWeek": [
	            "Вс",
	            "Пн",
	            "Вт",
	            "Ср",
	            "Чт",
	            "Пт",
	            "Сб"
	        ],
	        "monthNames": [
	            "Январь",
	            "Февраль",
	            "Март",
	            "Апрель",
	            "Май",
	            "Июнь",
	            "Июль",
	            "Август",
	            "Сентябрь",
	            "Октябрь",
	            "Ноябрь",
	            "Декабрь"
	        ],
	        "firstDay": 1
	    },
	});

	$('input[name="datetime"]').daterangepicker({
		'alwaysShowCalendars': true,
		"singleDatePicker": $('input[name="datetime"]').data('single'),
		"timePicker": true,
    	"timePicker24Hour": true,
		ranges: {
           'Сегодня': [moment(), moment()],
           'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
           'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
           'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
           'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "locale": {
	        "format": "DD.MM.YY H:mm",
	        "separator": "-",
	        "applyLabel": "Применить",
	        "cancelLabel": "Отмена",
	        "fromLabel": "От",
	        "toLabel": "До",
	        "weekLabel": "Н",
	        "customRangeLabel": "Свой диапазон",
	        "daysOfWeek": [
	            "Вс",
	            "Пн",
	            "Вт",
	            "Ср",
	            "Чт",
	            "Пт",
	            "Сб"
	        ],
	        "monthNames": [
	            "Январь",
	            "Февраль",
	            "Март",
	            "Апрель",
	            "Май",
	            "Июнь",
	            "Июль",
	            "Август",
	            "Сентябрь",
	            "Октябрь",
	            "Ноябрь",
	            "Декабрь"
	        ],
	        "firstDay": 1
	    },
	}, function() {

	});

	var currentdate = new Date();
	$('input[name="datetime"]').val(currentdate.getDate()+'.'+(currentdate.getMonth()+1)+'.'+currentdate.getFullYear().toString().slice(-2)+' '+currentdate.getHours()+':'+currentdate.getMinutes());
	$('input[name="datetime"]').on('show.daterangepicker', function(ev, picker) {
		$('.daterangepicker_input').css('display', 'block');
		var currentdate = new Date();
		$('.hourselect').find('option[value="'+currentdate.getHours()+'"]').attr('selected', 'selected');
		$('.minuteselect').find('option[value="'+currentdate.getMinutes()+'"]').attr('selected', 'selected');
	});

	$('.full-history').click(function(event) {
		event.preventDefault();
		var btn = $(this);

		if ($(btn).hasClass('showed')) {
			$('tr.service-archive').addClass('hide');
			$(btn).removeClass('showed');
			$(btn).text('Показать все обращение ('+$(btn).data('count')+')');
		} else {
			$('tr.service-archive').removeClass('hide');
			$(btn).addClass('showed');
			$(btn).text('Скрыть');
		}
	});

	$('.full-foreigner-history').click(function(event) {
		event.preventDefault();
		var btn = $(this);

		if ($(btn).hasClass('showed')) {
			$('.row.foreigner-history').addClass('hide');
			$(btn).removeClass('showed');
			$(btn).text('Показать историю изменений ('+$(btn).data('count')+')');
		} else {
			$('.row.foreigner-history').removeClass('hide');
			$(btn).addClass('showed');
			$(btn).text('Скрыть историю изменений');
		}
	});

	$('body').on('click', '.ajax-delete', function(event) {
		event.preventDefault();
		var btn = $(this);
		bootbox.confirm("Удалить?", function(result) {
			if (result) {
				$.ajax({
					url: $(btn).attr('href'),
					type: 'DELETE'
				})
				.done(function() {
					$(btn).parent().parent().remove();
				});
			}
		});
	});


	$('.confirm-close').click(function(event) {
		event.preventDefault();
		var btn = $(this);
		bootbox.confirm("Закрыть заявку?", function(result) {
			if (result) {
				window.location.replace($(btn).attr('href'));
			}
		});
	});

	$('.dropdown-logout').click(function (event) {
		event.stopPropagation();
	    event.preventDefault();
	    $('.dropdown-logout-menu').toggleClass('open');
	});

    $('.dropdown-report').click(function (event) {
		event.stopPropagation();
	    event.preventDefault();
	    $('.dropdown-report-menu').toggleClass('open');
	});

	$('.dropdown-export').click(function (event) {
		event.stopPropagation();
	    event.preventDefault();
	    $('.dropdown-export-menu').toggleClass('open');
	});

    $('.dropdown-payqr').click(function (event) {
       event.stopPropagation();
       event.preventDefault();
       $('.dropdown-payqr-menu').toggleClass('open');
    });

	$('.dropdown-menu-toggle').click(function (event) {
		event.stopPropagation();
	    event.preventDefault();
	    $(this).closest('.btn-group').toggleClass('open');
	});

	$('.btn-print').click(function(event) {
		event.preventDefault();
		window.print();
	});

	$('input, textarea').each(function() {
		if ($(this).attr('max')) {
			$(this).charCount({
				allowed: $(this).attr('max'),
				warning: $(this).attr('max')-10
			});
		}
	});

	$('form').preventDoubleSubmission();

	$("[data-toggle=popover]").popover({
		html: true,
		content: function() {
			return $('#popover-content').html();
		}
	});

	$("[data-toggle=popover-balance]").popover({
		html: true,
		content: function() {
			return $('#popover-balance-content').html();
		}
	});

	$('.btn-up').click(function(event) {
		event.preventDefault();
		$('body, html').animate({ scrollTop: 0 }, 400);
	});

	$('.capitalize').each(function() {
		$(this).text(capitalize($(this).text()));
	});

	$('.tr-link').click(function() {
		window.open($(this).data('link'), '_self');
	});

	$('.get-oktmo').click(function() {
		var address = $('#address').val() + $('input[name="address_line2"]').val() + $('input[name="address_line3"]').val();
		$(this).addClass('disabled');
		$(this).text('Получаем данные...');
		$('.oktmo-error').addClass('hide');
		$.ajax({
			url: '/operator/foreigners/oktmo',
			data: {
				address: address
			},
		})
		.done(function(data) {
			var oktmo = 'Не определен';
			$('input[name="oktmo"]').val(data.oktmo);
			$('input[name="district"]').val(data.district);
			$('.oktmo-data').removeClass('hide');
			$('#ifns_name option[value="'+data.ifns+'"]').attr('selected', 'selected');
			$("#ifns_name").val(data.ifns).trigger("change");
			if (data.oktmo) {
				oktmo = data.oktmo;
				$('input[name="oktmo_fail"]').val(0);
			} else {
				$('input[name="oktmo_fail"]').val(1);
			}
			$('.oktmo-data').find('.alert-info').html('<strong>ОКТМО: </strong>'+oktmo+'<br><strong>Район ОКТМО: </strong>'+data.district+'<br><strong>Адрес ОКТМО: </strong>'+data.address);
			$('.get-oktmo').text('Получить ОКТМО');
			$('.get-oktmo').removeClass('disabled');
		})
		.fail(function() {
			$('.oktmo-error').removeClass('hide');
			$('.get-oktmo').text('Получить ОКТМО');
			$('.get-oktmo').removeClass('disabled');
		});

	});

	$('.get-inn').click(function() {
		$(this).addClass('disabled');
		$(this).text('Получаем...');
		$('.inn-error').addClass('hide');
		$('.inn-captcha-error').addClass('hide');
		$('#captchaModal input[type="text"]').val('');

		var locked = true;

		$('#captchaModalWait').modal('show');

		setInterval(function() {
			if (locked) {
				$.ajax({
					url: '/ajax/innlock',
				})
				.done(function(data) {
					if (data == 'false') {
						locked = false;
						$('#captchaModalWait').modal('hide');
						$.ajax({
							url: '/operator/foreigners/inn'
						})
						.done(function(data) {
							if (data.captchaToken.length == 0) {
								$('.inn-error .alert').text(data.error);
								$('.inn-error').removeClass('hide');
								$('.get-inn').text('Получить ИНН');
								$('.get-inn').removeClass('disabled');
							} else {
								$('#captchaModal img').attr('src', 'https://service.nalog.ru/static/captcha.html?a='+data.captchaToken);
								$('#captchaModal input[name="captchaToken"]').val(data.captchaToken);
								$('#captchaModal').modal('show');
								$('input[name="inn_check"]').val('1');
							}
						})
						.fail(function() {
							$('.inn-error .alert').text('Связаться с сервером не удалось, попробуйте позже');
							$('.inn-error').removeClass('hide');
							$('.get-inn').text('Получить ИНН');
							$('.get-inn').removeClass('disabled');
						});
					}
				})
				.fail(function() {
					$('.inn-error .alert').text('Связаться с сервером не удалось, попробуйте позже');
					$('.inn-error').removeClass('hide');
					$('.get-inn').text('Получить ИНН');
					$('.get-inn').removeClass('disabled');
					$('#captchaModalWait').modal('hide');
				});
			}
		}, 1000);
	});

	$("#captchaModal").on('hide.bs.modal', function () {
        $('.get-inn').text('Получить ИНН');
		$('.get-inn').removeClass('disabled');
		$.get('/ajax/unlockinn');
    });

    $('#captchaModal').on('shown.bs.modal', function () {
        $('#captcha_text').focus();
    });

    $('#captcha-reload').click(function(event) {
    	$('#captchaModal input[type="text"]').val('');
		$.ajax({
			url: '/operator/foreigners/inn'
		})
		.done(function(data) {
			$('#captchaModal img').attr('src', 'https://service.nalog.ru/static/captcha.html?a='+data.captchaToken);
			$('#captchaModal input[name="captchaToken"]').val(data.captchaToken);
		});
    });

    function isBlank(str) {
		return (!str || /^\s*$/.test(str));
	}

    setInterval(function() {
    	if (!isBlank($('input[name="surname"]').val()) &&
		  !isBlank($('input[name="name"]').val()) &&
		  !isBlank($('input[name="birthday"]').val()) &&
		  !isBlank($('input[name="document_number"]').val()) &&
		  !isBlank($('input[name="document_date"]').val())) {
    		$('.get-inn').removeClass('disabled');
    		$('.inn-error-field').addClass('hide');
    	} else {
    		$('.get-inn').addClass('disabled');
    		$('.inn-error-field').removeClass('hide');
    	}
    }, 3000);

	$('.inn-captcha').click(function(event) {
		event.preventDefault();
		var btn = $(this);
		$(btn).addClass('disabled');
		$(btn).text('Получаем...');
		$('.inn-captcha-error').addClass('hide');
		$.ajax({
			url: '/operator/foreigners/inn',
			data: {
				captcha: $('input[name="captcha"]').val(),
				captchaToken: $('input[name="captchaToken"]').val(),
				surname: $('input[name="surname"]').val(),
				name: $('input[name="name"]').val(),
				middle_name: $('input[name="middle_name"]').val(),
				birthday: $('input[name="birthday"]').val(),
				document: $('input[name="document_series"]').val()+$('input[name="document_number"]').val(),
				document_date: $('input[name="document_date"]').val(),
			}
		})
		.done(function(data) {
			if (data.inn.length == 0) {
				$('.inn-captcha-error .alert').html(data.error);
				$('.inn-captcha-error').removeClass('hide');
				$('#captchaModal input[type="text"]').val('');
				$.ajax({
					url: '/operator/foreigners/inn'
				})
				.done(function(data) {
					$('#captchaModal img').attr('src', 'https://service.nalog.ru/static/captcha.html?a='+data.captchaToken);
					$('#captchaModal input[name="captchaToken"]').val(data.captchaToken);
				});
			} else {
				if (data.error) {
					$('.inn-error .alert').text(data.error);
					$('.inn-error').removeClass('hide');
				} else {
					$('input[name="inn"]').val(data.inn);
				}

				$('#captchaModal').modal('hide');
				$('.get-inn').text('Получить ИНН');
				$('.get-inn').removeClass('disabled');
				$.get('/ajax/unlockinn');
			}
		})
		.fail(function(data) {
			$('.inn-error .alert').text(data.error);
			$('.inn-error .alert').removeClass('hide');
		})
		.always(function() {
			$(btn).removeClass('disabled');
			$(btn).text('Получить');
		});
	});

	$('.qr-return').click(function(event) {
		event.preventDefault();
		var link = $(this).attr('href');
		var button = $(this).button('loading');

		$.ajax({
			url: link,
		})
		.done(function(data) {
			if (data.status) {
				$(button).hide();
				$(button).parent().find('.qr-return-print').removeClass('hide');
			} else {
				bootbox.alert("Невозможно отменить платеж");
			}

			$(button).button('reset');
		})
		.error(function() {
			bootbox.alert("Невозможно отменить платеж");
			$(button).button('reset');
		});

	});

	var countries = [
        'Армения',
		'Азербайджан',
		'Казахстан',
        'Киргизия',
		'Молдова',
		'Таджикистан',
		'Узбекистан',
		'Украина'
	];

	$('input[name="nationality"]').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		name: 'countries',
        limit: 8,
		source: substringMatcher(countries)
	});
	$('input[name="dms_nationality"]').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		name: 'countries',
        limit: 8,
		source: substringMatcher(countries)
	});

	$('.nationality').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		name: 'countries',
        limit: 8,
		source: substringMatcher(countries)
	});

	$('.modal-new-foreigner button').click(function() {
		var document_series = $('.modal-new-foreigner input[name="document_series"]').val();
		var document_number = $('.modal-new-foreigner input[name="document_number"]').val();

		if (document_number.length == 0) {
			$('.document-number-error').removeClass('hide');
			return false;
		}

		$.ajax({
			url: '/operator/foreigners/findclient',
			data: {
				document_series: document_series,
				document_number: document_number,
			}
		})
		.done(function(data) {
			if (data.count > 1) {
				$.each(data.foreigners, function(index, val) {
					$('.modal-new-foreigner-results tbody').append('<tr><td>'+ val.created_at +'</td><td><a href="/operator/foreigners/'+val.id+'/edit">'+ val.document_series + val.document_number +'</a></td><td>'+ val.surname +' '+ val.name +' '+ val.middle_name +'</td></tr>')
				});
				$('.modal-new-foreigner').addClass('hide');
				$('.modal-new-foreigner-table').removeClass('hide');
			} else {
				window.location.href = data.redirect;
			}
		});
	});

	$('#newApplication').on('hidden.bs.modal', function () {
		$('.modal-new-application input[name="document_series"]').val('');
		$('.modal-new-application input[name="document_number"]').val('');

		$('.modal-new-application').removeClass('hide');
		$('.modal-new-application-table').addClass('hide');
		$('.modal-new-application-results tbody').html('');
	});

	$('.btn-report').click(function(event) {
		var btn = $(this),
			val = $(btn).val();

		if (val == 'Экспорт') {
			$('.export').html('<input type="hidden" name="export" value="true">');
			$(btn).val('Готовим отчет...');
			$(btn).attr('disabled', 'disabled');

			setTimeout(function() {
				$(btn).val('Экспорт');
				$(btn).removeAttr('disabled');
			}, 3000);
		} else {
			$('.export').html('');
			$(btn).val('Готовим отчет...');
			$(btn).attr('disabled', 'disabled');
		}
		$('.form-inline').unbind("submit").submit();
	});

	$('body').on('click', 'a.disabled', function(event) {
	    event.preventDefault();
	});

	$('.data-table').DataTable({
		// paging: false,
		// order: $('.data-table').hasClass('data-table-services') ? [0, 'asc'] : [0, 'desc'],
		ordering: $('.data-table').hasClass('data-table-services') ? [2, 'asc'] : [],
		order: $('.data-table').hasClass('data-table-users') ? [2, 'asc'] : [],
		lengthMenu: [],
		iDisplayLength: 50,
		language: {
		  "processing": "Подождите...",
		  "search": "Поиск:",
		  "lengthMenu": "Показать _MENU_ записей",
		  "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
		  "infoEmpty": "Записи с 0 до 0 из 0 записей",
		  "infoFiltered": "(отфильтровано из _MAX_ записей)",
		  "infoPostFix": "",
		  "loadingRecords": "Загрузка записей...",
		  "zeroRecords": "Записи отсутствуют.",
		  "emptyTable": "В таблице отсутствуют данные",
		  "paginate": {
		    "first": "Первая",
		    "previous": "Предыдущая",
		    "next": "Следующая",
		    "last": "Последняя"
		  },
		  "aria": {
		    "sortAscending": ": активировать для сортировки столбца по возрастанию",
		    "sortDescending": ": активировать для сортировки столбца по убыванию"
		  }
		}
	});

    // test
    var table = $('#customSearchTable').DataTable();

    $('#customSearch').on( 'keyup', function () {
        table.search( this.value ).draw();
    } );

	if ($('#type_1').prop('checked')) {
		$('.organization').hide();
		$('.person').show();
	} else {
		$('.organization').show();
		$('.person').hide();
	}

	$('input[name="type"]').change(function(event) {
		if ($(this).val() == 1) {
			$('.organization').hide();
			$('.person').show();
		} else {
			$('.organization').show();
			$('.person').hide();
		}
	});

	$('input[name="is_complex"]').change(function(event) {
		if (!$(this).prop("checked")) {
			$('.complex').addClass('hide');
		} else {
			$('.complex').removeClass('hide');
		}
	});

	checkEntryData($('input[name="entry_data"]'));
	$('input[name="entry_data"]').change(function(event) {
		checkEntryData($('input[name="entry_data"]'));
	});

	var qr_id = null;
	$('.unrecognize-link').click(function(event) {
		$('#unrecognizeModal').modal('show');
		qr_id = $(this).data('id');

		$.ajax({
			url: '/operator/report/tax/unrecognize/parse',
			data: {
				qr_id: qr_id
			},
		})
		.done(function(data) {
			$('.unrecognize-modal input[name="name"]').val(data.name);
			$('.unrecognize-modal input[name="surname"]').val(data.surname);
			$('.unrecognize-modal input[name="middle_name"]').val(data.middle_name);
			$('.unrecognize-modal input[name="document_series"]').val(data.document_series);
			$('.unrecognize-modal input[name="document_number"]').val(data.document_number);
			$('.unrecognize-modal input[name="inn"]').val(data.inn);

			$('.search-document').attr('href', '/operator/foreigners?search='+data.document_series+data.document_number);
			$('.search-inn').attr('href', '/operator/foreigners?search='+data.inn);
			$('.search-name').attr('href', '/operator/foreigners?search='+data.surname+' '+data.name);
		});

	});

	$('.btn-link-submit').click(function(event) {
		event.preventDefault();
		var ig_id = $('.ig-id').val();

		$.ajax({
			url: '/operator/report/tax/unrecognize/link',
			data: {
				qr_id: qr_id,
				ig_id: ig_id,
			},
		})
		.always(function(data) {
			$('#unrecognizeModal').modal('hide');
			$('tr[data-trqr='+qr_id+']').hide();
		});
	});

	$('.btn-create').click(function(event) {
		$.ajax({
			url: '/operator/report/tax/unrecognize/save',
			data: {
				name: $('.unrecognize-modal input[name="name"]').val(),
				surname: $('.unrecognize-modal input[name="surname"]').val(),
				middle_name: $('.unrecognize-modal input[name="middle_name"]').val(),
				document_series: $('.unrecognize-modal input[name="document_series"]').val(),
				document_number: $('.unrecognize-modal input[name="document_number"]').val(),
				inn: $('.unrecognize-modal input[name="inn"]').val(),
				qr_id: qr_id,
			},
		})
		.done(function(data) {
			if (data.status == 'saved') {
				$('#unrecognizeModal').modal('hide');
				$('tr[data-trqr='+qr_id+']').hide();
			}
		});
	});

	$('.export-btn').click(function(event) {
		var btn = $(this);
		$(btn).addClass('disabled');
		window.open($(btn).attr('href'), '_self');
		setTimeout(function() {
			$(btn).removeClass('disabled');
		}, 8000);
	});

	$('.add-service').click(function(event) {
		event.preventDefault();
		$('#newService').modal('show');
	});

	$('.history-compare').each(function(index, el) {
		var type = $(this).data('history-compare'),
			history = $(this).text(),
			main = $('.main-compare[data-history-main='+type+']').text();

		var diff = JsDiff.diffChars(history, main);
		$(this).html('').append(formatDiffText(diff));
	});

	$('.add-balance-client-modal button[type="submit"]').click(function() {
		var sum = $('.add-balance-client-modal input[name="sum"]').val();
		var comment = $('.add-balance-client-modal input[name="comment"]').val();
		var client_id = $('.add-balance-client-modal input[name="client_id"]').val();
		var date = $('.add-balance-client-modal input[name="date"]').val();
		var number = $('.add-balance-client-modal input[name="number"]').val();
		var company_id = $('.add-balance-client-modal select option:selected').val();

		$.ajax({
			url: '/operator/clients/'+client_id+'/addbalance',
			type: 'POST',
			data: {
				sum: sum,
				comment: comment,
				date: date,
				number: number,
				company_id: company_id,
			}
		})
		.done(function(data) {
			location.reload();
		});
	});

	// $('.mu-table').slice(countRow).remove();
	var totalForeigner = 1;
	$('body').on('click', '.btn-add-muservice', function(event) {
		event.preventDefault();

		$('.foreigners-services').append(getForeignerServiceForm(totalForeigner));
		$('.nationality').typeahead({
			hint: false,
			highlight: true,
			minLength: 1
		}, {
			name: 'countries',
            limit: 8,
			source: substringMatcher(countries)
		});

		totalForeigner++;
		$('.count-foreigner').text(totalForeigner);
	});

	$('body').on('click', '.btn-ig-remove', function(event) {
		event.preventDefault();
		$(this).parentsUntil('.foreigners-services').remove();
		totalForeigner--;
		$('.count-foreigner').text(totalForeigner);
	});

	$('body').on('change', '.document_number', function(event) {
		event.preventDefault();

		var foreignerRow = $(this).parent().parent(),
			document_name = $(foreignerRow).find('.document_name').val(),
			document_series = $(foreignerRow).find('.document_series').val(),
			document_number = $(this).val();

		$.ajax({
			url: '/operator/muservices/info',
			data: {
				document_name: document_name,
				document_series: document_series,
				document_number: document_number
			},
		})
		.done(function(data) {
			$(foreignerRow).find('.surname').val(data.surname);
			$(foreignerRow).find('.name').val(data.name);
			$(foreignerRow).find('.middle_name').val(data.middle_name);
			$(foreignerRow).find('.birthday').val(data.birthday);
			$(foreignerRow).find('.nationality').val(data.nationality);
			if (data.document_name.length > 0) {
				$(foreignerRow).find('.document_name').val(data.document_name);
			}
		});
	});

	$('.cashless-pay').click(function(event) {
		event.preventDefault();
		var id = $(this).data('id'),
			type = $(this).data('type'),
			btn = $(this);
		$.ajax({
			url: '/operator/cashless/pay',
			data: {
				type: type,
				id: id,
			},
		})
		.always(function(data) {
			if (data.status == 'error') {
				bootbox.alert(data.data);
			} else {
            	$(btn).removeClass('btn-default').text('Оплачено');
				$(btn).addClass('btn-success disabled').text('Оплачено');

				var client_id = data.client_id,
					company_id = data.company_id,
					balance = data.balance;

				$('.company-balance[data-company-id="'+company_id+'"][data-client-id="'+client_id+'"]').each(function(index, el) {
					$(el).find('span').text(balance);
				});
			}
		});
	});

	$('.cash-pay').click(function(event) {
		event.preventDefault();
		var id = $(this).data('id'),
			type = $(this).data('type'),
			btn = $(this);
		$.ajax({
			url: '/operator/cash/pay',
			data: {
				type: type,
				id: id,
			},
		})
		.always(function() {
            $(btn).removeClass('btn-default').text('Оплачено');
			$(btn).addClass('btn-success disabled').text('Оплачено');
		});
	});

	$('select[name="host_id"]').change(function(event) {
		var client_id = $('select[name="host_id"] option:selected').val();

		$.ajax({
			url: '/operator/clients/find',
			data: {
				client_id: client_id
			},
		})
		.done(function(client) {
			$('#addAdress').parent().remove();
				if (client.type == 0) {
					if (client.organization_address_line2) {
						$('label[for="address"]').after('<div style="margin-bottom: 5px">Использовать адрес принимающей стороны: <span id="addAdress" style="color: rgba(52, 152, 219,1.0); border-bottom: 1px dotted; cursor: pointer">' + $.trim(client.organization_address_line2) + ' ' + $.trim(client.organization_address_line3) + '</span></div>');
					} else {
						$('label[for="address"]').after('<div style="margin-bottom: 5px">Использовать адрес принимающей стороны: <span id="addAdress" style="color: rgba(52, 152, 219,1.0); border-bottom: 1px dotted; cursor: pointer">адрес не указан</span></div>');
					}
				} else {
					if (client.address_line2) {
						$('label[for="address"]').after('<div style="margin-bottom: 5px">Использовать адрес принимающей стороны: <span id="addAdress" style="color: rgba(52, 152, 219,1.0); border-bottom: 1px dotted; cursor: pointer">' + $.trim(client.address_line2) + ' ' + $.trim(client.address_line3) + '</span></div>');
					} else {
						$('label[for="address"]').after('<div style="margin-bottom: 5px">Использовать адрес принимающей стороны: <span id="addAdress" style="color: rgba(52, 152, 219,1.0); border-bottom: 1px dotted; cursor: pointer">адрес не указан</span></div>');
					}
				}
			$('#addAdress').click(function(event) {
				if (client.type == 0) {
					$('input[name="address_line2"]').val(client.organization_address_line2);
					$('input[name="address_line3"]').val(client.organization_address_line3);
				} else {
					$('input[name="address_line2"]').val(client.address_line2);
					$('input[name="address_line3"]').val(client.address_line3);
				}
			});
		});
	});

	if ($('input[name="is_host_available"]').prop('checked')) {
		$('select[name="host_id"]').attr('disabled', 'disabled');
	}

	$('input[name="is_host_match"]').change(function(event) {
		var is_checked = $(this).prop('checked');
    	var host_id = $(this).attr('title');

		if (is_checked) {
			$('select[name="client_id"]').attr('disabled', 'disabled');
      $('select[name="client_id"]').val(host_id).trigger('change');
		} else {
			$('select[name="client_id"]').removeAttr('disabled');
      $('select[name="client_id"]').val('0').trigger('change');
		}
	});

	$('input[name="is_host_only"]').change(function(event) {
		var checkbox = $(this);

		if ($(checkbox).prop('checked')) {
            $('input[name="client_name"]').parent().removeClass('hide');
            $('input[name="client_type"]').parent().removeClass('hide');
            $('select[name="host_id"]').val(null).trigger('change');
            $('select[name="host_id"]').attr('disabled', 'disabled');
        } else {
            $('input[name="client_name"]').parent().addClass('hide');
            $('input[name="client_type"]').parent().addClass('hide');
            $('input[name="client_name"]').removeAttr('required');
            $('select[name="host_id"]').removeAttr('disabled');
		}
	});


	$( document ).on('click', '.registry-approve-change',function(event) {
		event.preventDefault();
		var btn = $(this);

		$.ajax({
			url: $(btn).attr('href')
		})
		.done(function(data) {
			if (data == 1) {
				$(btn).text('Подтвердить').removeClass('btn-default').addClass('btn-success');
				$(btn).parent().parent().find('td:first-child').removeClass('registry-status-2').addClass('registry-status-1');
			} else {
				$(btn).text('Отменить подтвердждение').addClass('btn-default').removeClass('btn-success');
				$(btn).parent().parent().find('td:first-child').addClass('registry-status-2').removeClass('registry-status-1');
			}
		});
	});

  $( document ).on('click', '.registry-remove',function(event) {
		event.preventDefault();
		var btn = $(this);

		$.ajax({
			url: $(btn).attr('href')
		})
		.done(function(data) {
			$(btn).parent().parent().fadeOut(300, function(){ $(this).remove();});
			$('.documents-rows tr').each(function(index, el) {
				$(this).find('td:first-child').text(index+1);
			});
		});
	});

	$('.send-to-registry').click(function(event) {
		event.preventDefault();
		var btn = $(this);

		$.ajax({
			url: $(btn).attr('href')
		})
		.done(function(data) {
			$(btn).text('В реестре').attr('disabled', 'disabled');
			$(btn).parent().parent().find('td:first-child').addClass('registry-status-1');
		});
	});

	var familyIteration = 0;
	$('.add-family').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: '/operator/foreigners/rvp/family-item',
			data: {
				iteration: familyIteration
			}
		})
		.done(function(data) {
			$('.family').append(data);
		});
		familyIteration++;
	});

	var workIteration = 0;
	$('.add-work').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: '/operator/foreigners/rvp/work-item',
			data: {
				iteration: workIteration
			}
		})
		.done(function(data) {
			$('.work').append(data);
		});
		workIteration++;
	});

	var quotaFamilyIteration = 0;
	$('.quota-add-family').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: '/operator/foreigners/quota/family-item',
			data: {
				iteration: quotaFamilyIteration
			}
		})
		.done(function(data) {
			$('.quota-family').append(data);
		});
		quotaFamilyIteration++;
	});

	var quotaWorkIteration = 0;
	$('.quota-add-work').click(function(event) {
		event.preventDefault();
		$.ajax({
			url: '/operator/foreigners/quota/work-item',
			data: {
				iteration: quotaWorkIteration
			}
		})
		.done(function(data) {
			$('.quota-work').append(data);
		});
		quotaWorkIteration++;
	});

	// $("#clients").easyAutocomplete({
	// 	url: function url(phrase) {
 //            return "/ajax/clients?search=" + phrase + "";
 //        },
 //        getValue: function getValue(element) {
 //            return element.name;
 //        },
 //        list: {
 //            match: {
 //                enabled: true
 //            }
 //        }
	// });
});

function getForeignerServiceForm(number) {
	var item = '<table class="mu-table mb5"><tr><td style="max-width:120px"><input class="form-control" name="foreigner['+number+'][document_name]" type="text" value="ПАСПОРТ" required="required"></td><td style="max-width:80px"><input class="form-control document_series" name="foreigner['+number+'][document_series]" type="text" value="" placeholder="Серия" pattern="[a-zA-Zа-яА-Я0-9\-]{0,7}" title="Только цифры и буквы"></td><td style="max-width:100px"><input class="form-control document_number" name="foreigner['+number+'][document_number]" type="text" value="" required="required" placeholder="Номер" pattern="[0-9]{1,11}" title="Только цифры"></td><td><input class="form-control surname" name="foreigner['+number+'][surname]" type="text" value="" required="required" placeholder="Фамилия"></td><td><input class="form-control name" name="foreigner['+number+'][name]" required="required" type="text" value="" placeholder="Имя"></td><td><input class="form-control middle_name" name="foreigner['+number+'][middle_name]" type="text" value="" placeholder="Отчество"></td><td><input class="form-control birthday" name="foreigner['+number+'][birthday]" required="required" type="date" value=""></td><td><input class="form-control nationality" name="foreigner['+number+'][nationality]" required="required" placeholder="Гражданство" name="nationality" type="text" value=""autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;"></td><td><select class="form-control" name="foreigner['+number+'][type_appeal]"><option value="0">Первичная регистрация</option><option value="1">Продление</option><option value="2">Трудовой договор</option></select></td><td><a href="" class="btn btn-danger btn-sm btn-ig-remove ml10"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td></tr></table>';
	return item;
}

function formatDiffText(diff) {
	var fragment = document.createDocumentFragment(),
		color = '',
		span = null;

	diff.forEach(function(part) {
		color = part.added ? 'diff-green' : part.removed ? 'diff-red' : 'diff-black';
		span = document.createElement('span');
        span.classList.add(color);
		span.appendChild(document.createTextNode(part.value));
	    fragment.appendChild(span);
	});

	return fragment;
}

function checkEntryData(checkbox) {
	if ($(checkbox).is(":checked")) {
		$('.entry-data').show();
	} else {
		$('.entry-data').hide();
	}
}

(function($) {

	$.fn.charCount = function(options){

		// default configuration properties
		var defaults = {
			allowed: 140,
			warning: 25,
			css: 'counter',
			counterElement: 'span',
			cssWarning: 'warning',
			cssExceeded: 'exceeded',
			counterText: ''
		};

		var options = $.extend(defaults, options);

		function calculate(obj){
			var count = $(obj).val().length;
			var available = options.allowed - count;
			var warning = (options.allowed / 100) * 25;
			if(available <= warning && available >= 0){
				$(obj).next().addClass(options.cssWarning);
			} else {
				$(obj).next().removeClass(options.cssWarning);
			}
			if(available < 0){
				$(obj).next().addClass(options.cssExceeded);
			} else {
				$(obj).next().removeClass(options.cssExceeded);
			}
			$(obj).next().html(options.counterText + available);
		};

		this.each(function() {
			$(this).after('<'+ options.counterElement +' class="' + options.css + '">'+ options.counterText +'</'+ options.counterElement +'>');
			calculate(this);
			$(this).keyup(function(){calculate(this)});
			$(this).change(function(){calculate(this)});
		});

	};

})(jQuery);

jQuery.fn.preventDoubleSubmission = function() {
	$(this).on('submit',function(e){
		var $form = $(this);

		if ($form.data('submitted') === true) {
			e.preventDefault();
		} else {
			$form.data('submitted', true);
		}
	});

	return this;
};

var capitalize = function(text) {
	return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
}


var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};
