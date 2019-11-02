<?php
/***
 * Template Name: 행사 글 쓰기
 */
$post_id = '';
$title = '';
$content = '';
$tagstr = '';
$post_thumbnail = '';
$post_thumbnail_id = '';
$cat_name = '선택하세요';
$cat_slug = '';
$start_date = '';
$finish_date = '';
$addr = '';
$lat = '';
$lng = '';
if (isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];
    $user_id = get_current_user_id();
    $post = get_post($post_id);
    if ($post->post_author == $user_id || is_super_admin()) {
        $title = $post->post_title;
        $content = $post->post_content;
        $tags = wp_get_post_tags($post_id, 'array');
        $tagstr = '';
        foreach ($tags as $key => $tag) {
            if ($key != 0) $tagstr .= ',';
            $tagstr .= $tag->name;
        }
        $post_thumbnail = get_the_post_thumbnail($post_id, array(775, 240));
        $post_thumbnail_id = get_post_thumbnail_id($post_id);
        $category = wp_get_post_terms($post_id, 'research_cat');
        if ($category) {
            $cat_slug = $category[0]->slug;
            $cat_name = $category[0]->name;
        }
        $start_date = get_field('start_date', $post_id);
        $start_date = date('m/d/Y', strtotime($start_date));
        $finish_date = get_field('finish_date', $post_id);
        $finish_date = date('m/d/Y', strtotime($finish_date));
        $gmap = get_field('event_addr', $post_id);
        if ($gmap) {
            $addr = $gmap['address'];
            $lat = $gmap['lat'];
            $lng = $gmap['lng'];
        }
    }
}
$key = "AIzaSyDi1NbC3FwevvtA_s4hnS4EXaz2hlWU5FY";
get_header() ?>
    <!-- 서브 영역 -->
<?php //acf_form(); ?>
    <div class="fc02 event" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt>행사</dt>
            </dl>
        </div>
        <div class="inner tc01">
            <!-- 컨텐츠 영역 -->
            <h1 class="th05">글쓰기</h1>
            <form id="writeEventForm" class="cf05 writeEventForm" method="post" action="/wp-admin/admin-ajax.php"
                  enctype="multipart/form-data">
                <input type="hidden" name="action" value="soinnolab_write"/>
                <input type="hidden" name="section" value="events"/>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>"/>
                <table>
                    <tbody>
                    <tr class="tit">
                        <th><label for="write_title">제목</label><i>*</i></th>
                        <td><input type="text" id="write_title" name="write_title" value="<?php echo $title; ?>"/></td>
                    </tr>
                    <tr class="cat">
                        <th><label for="write_category">연구분야</label><i>*</i></th>
                        <td>
                            <input type="hidden" id="write_category" name="write_category"
                                   value="<?php echo $cat_slug; ?>"/>
                            <dl class="ng03" data-sync-id="write_category"
                                data-current-value="<?php echo $cat_slug; ?>">
                                <!-- 값을 선택하면 'board_search_option'의 id값을 가진 input에 값을 전송함 -->
                                <dt><a href="#select1"><?php echo $cat_name; ?></a></dt>
                                <dd>
                                    <ul>
                                        <?php soinnolab_taxonomies_links('research_cat', '<li>', '</li>', 'data-value'); ?>
                                    </ul>
                                </dd>
                            </dl>
                        </td>
                    </tr>
                    <tr class="thumb">
                        <th><label for="write_thumb">썸네일 이미지 첨부</label></th>
                        <td>
                            <?php if ($post_thumbnail_id) { ?>
                                <p><em><?php echo $post_thumbnail; ?></em></p>
                                <p>
                                    <button class="btn03 thumbnail delete" type="button">삭제하기</button>
                                </p>
                                <input type="hidden" name="post_thumbnail_id" value="<?php echo $post_thumbnail_id; ?>">
                            <?php } else { ?>
                                <input type="file" id="write_thumb" name="write_thumb"/>
                                <i>이미지를 업로드 하지 않을 경우, 기본 썸네일로 노출됩니다</i>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr class="date">
                        <th>행사 날짜 선택</th>
                        <td>
                            <link rel="stylesheet"
                                  href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                            <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                            <script>
                                $(function () {
                                    $("#start_date").datepicker();
                                    $("#finish_date").datepicker();
                                });
                            </script>
                            <span>
								<label for="start_date">시작일</label>
								<input type="text" id="start_date" name="start_date"
                                       value="<?php echo $start_date; ?>"/>
								<i class="xi-calendar-add"></i>
							</span>
                            ~
                            <span>
								<label for="finish_date">종료일</label>
								<input type="text" id="finish_date" name="finish_date"
                                       value="<?php echo $finish_date; ?>"/>
								<i class="xi-calendar-add"></i>
							</span>
                        </td>
                    </tr>
                    <tr class="place">
                        <th><label for="write_place">행사 장소 작성</label></th>
                        <td>
                            <input type="text" id="write_place" name="write_place" placeholder="행사가 진행되는 곳의 주소를 입력해주세요"
                                   value="<?php echo $addr; ?>"/>
                            <input type="hidden" id="lat" name="lat" value="<?php echo $lat; ?>"/>
                            <input type="hidden" id="lng" name="lng" value="<?php echo $lng; ?>"/>
                            <div id="map" class="c_map03"></div>
                        </td>
                    </tr>
                    <tr class="content">
                        <th><label for="write_content">행사내용과 관련된<br/>글 작성</label><i>*</i></th>
                        <td><?php wp_editor($content, 'content', array('textarea_name' => 'write_content', 'drag_drop_upload' => true, 'media_buttons' => false)); ?></td>
                    </tr>
                    <tr class="tag">
                        <th><label for="write_tag">태그(쉼표로 구분)</label></th>
                        <td><input type="text" id="write_tag" name="write_tag" value="<?php echo $tagstr; ?>"/></td>
                    </tr>
                    </tbody>
                </table>
                <div class="fc04">
                    <button class="btn02" type="submit">등록하기</button>
                </div>
            </form>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?sensor=false&region=KR&key=<?php echo $key; ?>&libraries=places"></script>
    <script>
        //PLACE SEARCH PLACE DETAILS PLACE AUTOCOMPLETE
        function initialize() {
            var lat = Number($("#lat").val());
            var lng = Number($("#lng").val());
            if (lat == 0 && lng == 0) {
                var mapOptions = {
                    center: {lat: 37.5791813, lng: 126.9644653},
                    zoom: 13,
                    scrollwheel: false
                };
                var map = new google.maps.Map(document.getElementById('map'), mapOptions);
                var marker = new google.maps.Marker({map: map});
            } else {
                var myLatLng = {lat: lat, lng: lng};
                // Create a map object and specify the DOM element for display.
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: myLatLng,
                    scrollwheel: false,
                    zoom: 13
                });

                // Create a marker and set its position.
                var marker = new google.maps.Marker({
                    map: map,
                    position: myLatLng,
                    title: 'Hello World!'
                });
            }

            var input = (document.getElementById('write_place'));
            /** @type {HTMLInputElement} */

                // Create the autocomplete helper, and associate it with
                // an HTML text input box.
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker)
            });

            // Get the full place details when the user selects a place from the
            // list of suggestions.
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                infowindow.close();
                var place = autocomplete.getPlace();
                if (!place.geometry) return;

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                // Set the position of the marker using the place ID and location.
                marker.setPlace(/** @type {!google.maps.Place} */ ({
                    placeId: place.place_id,
                    location: place.geometry.location
                }));
                marker.setVisible(true);

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + place.formatted_address + '</div>'); //+ 'Place ID: ' + place.place_id + '<br>'
                infowindow.open(map, marker);

                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                jQuery('#lat').val(lat);
                jQuery('#lng').val(lng);
            });
        }

        // Run the initialize function when the window has finished loading.
        google.maps.event.addDomListener(window, 'load', initialize);

        document.getElementById("writeEventForm").onkeypress = function (e) {
            var key = e.charCode || e.keyCode || 0;
            if (key == 13) {
                e.preventDefault();
            }
        }
    </script>
    <!-- //서브 컨텐츠 영역 -->
<?php get_footer(); ?>