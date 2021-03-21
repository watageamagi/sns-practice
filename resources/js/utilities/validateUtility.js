
export function classValidate (check, classType) {
    if (!check._model) {
        throw new Error('check is get _model property not found')
    }

    if (check._model !== classType) {
        throw new Error(`${check._model}と${classType}が一致しません`)
    }
}