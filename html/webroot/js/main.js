/**
 * セレクトプルダウンにてセレクト数に応じてheightを変える
 */
document.addEventListener('DOMContentLoaded', function () {
    const select = document.querySelector('select'); // select要素の取得

    // 選択が変更されたときに呼び出される関数
    function adjustSelectHeight() {
        // ここで適切な高さを設定する処理を追加
        select.style.height = 'auto'; // 高さをリセット
        select.style.height = select.scrollHeight + 'px'; // スクロールの高さに合わせて設定
    }

    adjustSelectHeight(); // 初期読み込み時に高さを設定
    select.addEventListener('change', adjustSelectHeight);
});
