<?php
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="container">
    <div id="main">
        <div class="not-found">
            <strong>404</strong>
            <h1>The page cannot be found.</h1>
            <hr>
            <form action="#" method="post">
                    <div class="container-inline">
                        <div class="input-append">
                            <input placeholder="Search" type="text" name="search_block_form" >
                            <input type="submit" class="btn">
                        </div>
                    </div>
            </form>

            <p>
                Please use search box or <?=anchor('', 'Return Back')?>
            </p>
        </div>
    </div>
</div>