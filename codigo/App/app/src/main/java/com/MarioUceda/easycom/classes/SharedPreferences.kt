package com.MarioUceda.easycom.classes

import android.content.Context
import android.content.SharedPreferences
import com.MarioUceda.easycom.R

class SharedPreferences(context: Context?) {
    private var prefs: SharedPreferences = context!!.getSharedPreferences(context.getString(R.string.app_name), Context.MODE_PRIVATE)

    companion object {
        const val USER_LOGGED = "user_logged"
        const val USER_ID = "user_id"
        const val USER_NAME = "user_name"
        const val USER_EMAIL = "user_email"
        const val USER_CREATED_AT = "user_created_at"
    }

    /**
     * Function to save user data
     */
    fun saveUserData(user: Usuario) {
        val editor = prefs.edit()
        editor.putBoolean(USER_LOGGED, true)
        editor.putString(USER_ID, user.id)
        editor.putString(USER_NAME, user.nombre)
        editor.putString(USER_EMAIL, user.email)
        editor.putString(USER_CREATED_AT, user.created_at)
        editor.apply()
    }

    /**
     * Function to get user data
     */
    fun getUserData(): Usuario {
        val user = Usuario(
            prefs.getString(USER_ID, null)!!,
            prefs.getString(USER_NAME, null)!!,
            prefs.getString(USER_EMAIL, null)!!,
            prefs.getString(USER_CREATED_AT, null)!!
        )
        return user
    }

    /**
     * Function to check if user is logged
     */
    fun isUserLogged(): Boolean {
        return prefs.getBoolean(USER_LOGGED, false)
    }

    /**
     * Function to clear user data
     */
    fun clearUserData() {
        val editor = prefs.edit()
        editor.clear()
        editor.apply()
    }


}