import Mixin from '@ember/object/mixin';
import { isBlank } from '@ember/utils'

export default Mixin.create({

    _visitedRecordIds: {},

    serializeBelongsTo(snapshot, json, relationship)
    {
        const relKey = relationship.key;
        const relKind = relationship.kind;

        if (
            json
            && this.get(`attrs.${relKey}.serialize`) === "phone"
            && relationship.meta.type === "phone"
        ) {

            const serializedData = this.serializeRecord(snapshot.belongsTo(relKey));

            if (!isBlank(serializedData)) {
                json.attributes[relKey] = serializedData.attributes;
            } else {
                json.attributes[relKey] = null;
            }

        } else {
            this._super(...arguments);
        }
    },

    serializeRecord(obj)
    {
        if (!obj) {
            return null;
        }

        const serialized = obj.serialize({__isSaveRelationshipsMixinCallback: true});

        if (obj.id) {
            this.get('_visitedRecordIds')[obj.id] = {};
            serialized.data.attributes.id = obj.id;
        }

        if (!serialized.data.attributes) {
            serialized.data.attributes = {};
        }

        serialized.data.attributes.__id__ = obj.record.get('_internalModel')[Ember.GUID_KEY];
        this.get('_visitedRecordIds')[serialized.data.attributes.__id__] = {};

        for (let relationshipId in serialized.data.relationships) {
            if (this.get('_visitedRecordIds')[relationshipId]) {
                delete serialized.data.relationships[relationshipId];
            }
        }

        if (serialized.data.relationships === {}) {
            delete serialized.data.relationships;
        }

        return serialized.data;
    },
})
