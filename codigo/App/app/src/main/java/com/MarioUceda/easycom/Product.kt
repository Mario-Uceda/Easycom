package com.MarioUceda.easycom

data class Product(
    var id: String,
    var name: String,
    var url: String,
    var img: String,
    val description: String = "",
    val technicalSpecs: String = ""
) {
    constructor(id: String, url: String) : this(id, "", url, "", "")
}