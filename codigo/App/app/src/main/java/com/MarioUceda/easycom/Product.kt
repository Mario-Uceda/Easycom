package com.MarioUceda.easycom

import java.io.Serializable

data class Product(
    var id: String,
    var name: String,
    var url: String,
    var img: String,
    var description: String = "",
    var technicalSpecs: String = ""
) : Serializable {
    constructor(id: String, url: String) : this(id, "", url, "", "")

    override fun toString(): String {
        return ("id: $id name: $name url: $url img: $img description: $description technicalSpecs: $technicalSpecs")
    }
}