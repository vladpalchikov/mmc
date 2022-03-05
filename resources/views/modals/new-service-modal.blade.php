<div class="modal fade new-service-modal" tabindex="-1" role="dialog" id="newService">
    <form action="/operator/foreigners/{{ $foreigner->id }}/storeservices" id="storeServicesForm" method="POST">
        <div class="modal-dialog" style="width: 720px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Новая услуга</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group choice-group col-md-12">
                            <label for="payment_method" class="choices-label">Способ оплаты</label>
                            <div class="choice-wrapper">
                                <input id="payment_method_0" checked="checked" name="payment_method" type="radio" value="0">
                                <label class="label-class" for="payment_method_0">Наличными в кассу</label>
                            </div>
                            <div class="choice-wrapper">
                                <input id="payment_method_1" name="payment_method" type="radio" value="1">
                                <label class="label-class" for="payment_method_1">Безналичная оплата</label>
                            </div>
                        </div>

                        <div class="choice-group col-md-12">
                            <label for="client_id" class="choices-label">Плательщик (представитель)</label>
                            <select name="client_id" class="form-control clients nos2" style="width: 100%"></select>
                        </div>
                        <div class="form-group choice-group col-md-12">
                            @if ($foreigner->host)
                              <div class="choice-wrapper">
                                  <input id="is_host_match" name="is_host_match" title="{{ $foreigner->host->id }}" type="checkbox">
                                  <label class="label-class" for="is_host_match">Совпадает с принимающей стороной <strong>"{{ $foreigner->host->name }}"</strong></label>
                              </div>
                            @endif
                        </div>

                        @if (isset($services))
                            <div class="form-group choice-group col-md-12">
                                <h4>Услуги</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        @foreach ($services->take(($services->count() / 2)+1)->get() as $service)
                                            <input id="services_{{ $service->id }}" name="services[]" type="checkbox" value="{{ $service->id }}" class="pull-left">
                                            <label class="control-label mt0" for="services_{{ $service->id }}">{{ $service->name }}<br></label>
                                        @endforeach
                                    </div>

                                    <div class="col-md-6">
                                        @foreach ($services->skip(($services->count() - ($services->count() / 2))+1)->get() as $service)
                                            <input id="services_{{ $service->id }}" name="services[]" type="checkbox" value="{{ $service->id }}" class="pull-left">
                                            <label class="control-label mt0" for="services_{{ $service->id }}">{{ $service->name }}<br></label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer modal-new-service">
                    <button class="btn btn-success" type="submit">Добавить</button>
                </div>
            </div>
        </div>
    </form>
</div>
