<div class="table-responsive">
    <table class="table table-striped table-bordered base-style">
        <thead>
        <tr>
            <th># Index</th>
            <th>Post Title</th>
            <th>Reporter Name</th>
            <th>Reporter Type</th>
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
                    {{ $record['post']['caption'] }}
                </td>
                <td>
                    {{ $record['user']['first_name'] }}
                </td>

                <td>
                    {{ $record['reported_type'] }}
                </td>

                <td>
                    {{ $record['comments'] }}
                </td>
                <td>
                    <button type="button" class="btn mr-1 mb-1 btn-outline-danger btn-sm reported_post" data-type="blocked" data-uuid="<?= $record['reported_post_uuid'] ?>">Blocked</button>
                </td>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th># Index</th>
            <th>Post Title</th>
            <th>Reporter Name</th>
            <th>Reporter Type</th>
            <th>Comments</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
</div>
