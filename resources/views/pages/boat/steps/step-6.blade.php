<h6><i class="step-icon ft-home"></i> Step 6</h6>
<fieldset>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Price :</label>
                <input type="text" disabled class="form-control"  value="{{$record['price']}}">
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Price Unit:</label>
                <input type="text" disabled class="form-control"  value="{{$record['price_unit']}}">
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    @foreach ($record['discount'] as $key => $item)
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Discount- {{ ++$key }}</label>
                    <input type="text" disabled class="form-control"  value="{{ $item['discount_after'] }} hours">
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

    @endforeach
</fieldset>
