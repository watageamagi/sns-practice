/**
 * 非同期にBlobをテキストとして読み込みこみ
 */
export default blob => {
    const fileReader = new FileReader();

    return new Promise((resolve, reject) => {
        fileReader.onerror = () => {
            fileReader.abort();
            reject();
        };

        fileReader.onload = () => {
            resolve(fileReader.result);
        };

        fileReader.readAsText(blob);
    });
};
