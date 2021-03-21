import Vue from 'vue'
import LocationIcon from '../components/Ui/LocationIcon'

// create a constructor from a Vue component
const LocationIconConstructor = Vue.extend(LocationIcon)

export const getColoredIconUrl = (fillColor, strokeColor) => {
    // create a Vue element with required props
    const iconComponent = new LocationIconConstructor({ propsData: { fillColor, strokeColor } })
    // mount the component shadow DOM
    iconComponent.$mount()
    // get icon DOM element
    const iconDom = iconComponent.$el
    // generate an html string from an element
    const iconString = new XMLSerializer().serializeToString(iconComponent.$el)
    // finally, generate a data url from a string
    return 'data:image/svg+xml;charset=UTF-8;base64,' + btoa(iconString)
}