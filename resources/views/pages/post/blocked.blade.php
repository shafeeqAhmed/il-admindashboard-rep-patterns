<div class="table-responsive">
    <table class="table table-striped table-bordered base-style">
        <thead>
        <tr>
            <th># Index</th>
            <th>Post Title</th>
            <th>Post Type</th>
            <th>Comments</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $key => $record)
            <tr>
                <td class="text-center">
                    {{ ++$key }}
                </td>
                <td class="text-center">
                    {{ $record['caption'] }}
                </td>
                <td class="text-center align-content-center">
                    @if ($record['media_type'] == 'image')
                        <span class="badge badge-success la la-file-image-o">
                                                                    {{ ucfirst($record['media_type']) }}
                                                                </span>
                    @else
                        <span class="badge badge-primary  la la-file-image-o">
                                                                    {{ ucfirst($record['media_type']) }}
                                                                </span>
                    @endif
                </td>
                <td class="text-center">
                    {{ $record['text'] }}
                </td>
                <td>
                    <button type="button" class="btn mr-1 mb-1 btn-outline-danger btn-sm reported_post" data-type="unblocked" data-uuid="<?= $record['reportedRecord']['reported_post_uuid'] ?>">Un Blocked</button>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th># Index</th>
            <th>Post Title</th>
            <th>Post Type</th>
            <th>Comments</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
</div>
