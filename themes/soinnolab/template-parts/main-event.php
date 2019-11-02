<div class="leftA">
    <h2>오늘의 행사</h2>
    <ul>
        <li>
            <dl>
                <dt>총 등록</dt>
                <dd><?php soinnolab_get_event_count(); ?></dd>
            </dl>
        </li>
        <li>
            <dl>
                <dt>오늘 등록</dt>
                <dd><?php soinnolab_get_event_count('today'); ?></dd>
            </dl>
        </li>
        <li>
            <dl>
                <dt>이주 행사</dt>
                <dd><?php soinnolab_get_event_count('event'); ?></dd>
            </dl>
        </li>
    </ul>
</div>