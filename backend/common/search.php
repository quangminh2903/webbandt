<form action="" method="get">
    <div class="controls">
        <div class="input-append">
            <input type="text" class="span4 m-wrap" placeholder="Search..." name="search" id="search" value="<?php echo !empty($search) ? $search : ''; ?>">

            &ensp;

            <button class="btn btn-info" type="submit" style="margin-bottom: 10px;">
                <i class="icon-search"></i>
                &ensp;&nbsp;Search
            </button>

            &ensp;

            <button class="btn btn-invert" type="Reset" id="reset" style="margin-bottom: 10px;">
                <i class="icon-refresh"></i>
                &ensp;&nbsp;Reset
            </button>
        </div>
    </div>
</form>