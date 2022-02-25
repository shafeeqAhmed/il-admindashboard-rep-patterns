<h6><i class="step-icon ft-home"></i> Step 1 </h6>
<fieldset>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="boat_type_name">Boat Type Name :</label>
                <input type="text" disabled class="form-control" id="boat_type_name" name="boat_type_name" value="{{$record['boat_type']['name']}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div>
                <img style="height: 300px; width: 300px" alt="" src="{{ $record['boat_type']['pic'] }}">

            </div>
        </div>
    </div>
</fieldset>
