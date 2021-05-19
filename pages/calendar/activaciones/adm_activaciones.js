import * as registroHoras from './registrohoras.js';
import * as licencias from '../licencias/licencias.js';

const user_calendar = await registroHoras.init(new Date(), new Date());
licencias.init(new Date(), new Date(), user_calendar);