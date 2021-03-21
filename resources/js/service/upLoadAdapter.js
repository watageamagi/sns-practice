/**
 * ckeditor用画像アップロード
 */
export default class UploadAdapter {
    constructor(loader, invest, mediaList=[]) {
        this.loader = loader
        this.invest = invest
        this.mediaList = mediaList
    }

    async upload() {
        const file = await this.loader.file
        const params = new FormData()
        params.append("file", file)
        params.append("invest_id", this.invest.id)
        const {data} = await axios.post("/api/admin/invest-image", params)
        const url = data.url
        this.mediaList = data.mediaList
        return { default: url }
    }
}
