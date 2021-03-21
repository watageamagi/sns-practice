import linq from 'linq'
import { snakeToCamel, camelToSnake } from './stringUtility'

/**
 *
 * @param roomId
 * @returns {Promise<*>}
 */
export async function getChatDB (roomId) {
    const { chatCollection } = Laravel.firebaseConfig
    return await db.collection(chatCollection).doc(roomId).collection('chats')
}
