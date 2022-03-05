<div class="modal fade add-balance-client-modal new-service-modal" tabindex="-1" role="dialog" id="addBalance">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Пополнение баланса</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label required">Сумма, руб.</label>
                        <input class="form-control" autofocus="autofocus" name="sum" type="text">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Дата поступления</label>
                        <input class="form-control" name="date" type="text" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Номер платежного поручения</label>
                        <input class="form-control" name="number" type="text" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Оператор</label>
                        <select name="company_id" id="company_id" class="selectModal">
                            @foreach (\MMC\Models\Company::all() as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Комментарий</label>
                        <input class="form-control" name="comment" type="text">
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-new-foreigner">
                <button class="btn btn-success" type="submit">Добавить</button>
            </div>
        </div>
    </div>
</div>
