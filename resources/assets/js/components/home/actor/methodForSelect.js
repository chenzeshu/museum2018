/**
 * 主要是筛选框点击X按钮清除选项
 * @type {{clearPerfType(): void, clearPerfTroupe(): void, clearPerfAddr(): void}}
 */
export const filterSelect = {
    clearPerfType(){
        this.$refs.perfType.clearSingleSelect()
    },
    clearPerfTroupe(){
        this.$refs.perfTroupe.clearSingleSelect()
    },
    clearPerfAddr(){
        this.$refs.perfAddr.clearSingleSelect()
    }
}