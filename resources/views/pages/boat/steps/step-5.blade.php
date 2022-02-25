<h6><i class="step-icon ft-home"></i> Step 5</h6>
<fieldset>
    @foreach ($record['boat_captains'] as $item)
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Captain Name :</label>
                    <input type="text" disabled class="form-control"  value="{{$item['captain_user']['first_name']}}">
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div>
                    <img  class="position-relative" alt="" src="{{ $item['captain_user']['profile_pic'] }}">

                </div>
            </div>
        </div>
    @endforeach

</fieldset>
